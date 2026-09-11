<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    /**
     * Show the form for editing the doctor's own profile.
     */
    public function edit()
    {
        $user = Auth::user();

        // Ensure user is doctor
        if (!$user->hasRole('doctor')) {
            abort(403);
        }

        $profile = $user->doctorProfile;

        // Create profile if it doesn't exist (though it should ideally exist on registration/role assignment)
        if (!$profile) {
            $profile = DoctorProfile::create([
                'user_id' => $user->id,
                'license_number' => 'PENDING-' . $user->id, // Placeholder
            ]);
        }

        $specialties = Specialty::active()->get();

        return view('doctor.profile.edit', compact('profile', 'specialties'));
    }

    /**
     * Update the doctor's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->doctorProfile;

        $validated = $request->validate([
            'license_number' => 'required|string|max:100|unique:doctor_profiles,license_number,' . $profile->id,
            'bio' => 'nullable|array',
            'bio.en' => 'nullable|string',
            'education' => 'nullable|array',
            'experience_years' => 'nullable|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'specialties' => 'array',
            'specialties.*' => 'exists:specialties,id',
            'accepts_walk_ins' => 'boolean',
            'cancellation_hours' => 'nullable|integer|min:0',
            'reschedule_hours' => 'nullable|integer|min:0',
            'accepts_walks_in' => 'boolean',
            'is_available' => 'boolean',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $profile->update($validated);
        
        $user->update(['phone' => $validated['phone'] ?? null]);

        if (isset($validated['specialties'])) {
            $profile->specialties()->sync($validated['specialties']);
        }

        return redirect()->route('doctor.profile.show', $profile->id)
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Display the specified doctor profile (Public).
     */
    public function show($id)
    {
        $profile = DoctorProfile::with(['user', 'specialties', 'locations'])->findOrFail($id);

        // Ensure the doctor user is active
        if (!$profile->user->is_active) {
            abort(404);
        }

        return view('doctor.profile.show', compact('profile'));
    }
}
