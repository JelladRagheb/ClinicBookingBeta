<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminDoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     */
    public function index()
    {
        // Get users who have the 'doctor' role
        $doctors = User::whereHas('roles', function($q) {
            $q->where('name', 'doctor');
        })->with('doctorProfile.specialties')->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for editing the specified doctor.
     */
    public function edit(User $doctor)
    {
        // Ensure user is actually a doctor
        if (!$doctor->hasRole('doctor')) {
            abort(404);
        }

        $doctor->load('doctorProfile.specialties');
        $specialties = Specialty::all();

        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update the specified doctor in storage.
     */
    public function update(Request $request, User $doctor)
    {
        if (!$doctor->hasRole('doctor')) {
            abort(404);
        }

        $validatedUser = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($doctor->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($doctor->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $validatedProfile = $request->validate([
            'license_number' => 'required|string|max:50',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'bio_en' => 'nullable|string',
            'specialties' => 'required|array',
            'specialties.*' => 'exists:specialties,id'
        ]);

        try {
            DB::beginTransaction();

            // Update User
            $userData = [
                'first_name' => $validatedUser['first_name'],
                'last_name' => $validatedUser['last_name'],
                'email' => $validatedUser['email'],
                'phone' => $validatedUser['phone'],
                'is_active' => $request->has('is_active') ? true : false,
            ];

            if (!empty($validatedUser['password'])) {
                $userData['password'] = Hash::make($validatedUser['password']);
            }

            $doctor->update($userData);

            // Update Doctor Profile
            $profile = $doctor->doctorProfile;
            
            // Handle bio translation (simple EN fallback for now)
            $existingBio = $profile->bio ?? [];
            $existingBio['en'] = $validatedProfile['bio_en'];

            $profile->update([
                'license_number' => $validatedProfile['license_number'],
                'experience_years' => $validatedProfile['experience_years'],
                'consultation_fee' => $validatedProfile['consultation_fee'],
                'bio' => $existingBio,
            ]);

            // Sync Specialties
            $profile->specialties()->sync($validatedProfile['specialties']);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating doctor: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified doctor account or deactivate.
     */
    public function destroy(User $doctor)
    {
        if (!$doctor->hasRole('doctor')) {
            abort(404);
        }

        // Hard delete or deactivate depending on requirements.
        // Usually, for doctors with appointments, we should only deactivate.
        // We will just soft-delete the user since SoftDeletes is on the model
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor account has been disabled/deleted successfully.');
    }
}
