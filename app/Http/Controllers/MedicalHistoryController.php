<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * Patient views their own history.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasRole('patient')) {
            abort(403);
        }

        $patient = PatientProfile::where('user_id', $user->id)
            ->with(['medicalHistories', 'consultations.doctor.user', 'prescriptions.items', 'prescriptions.doctor.user'])
            ->firstOrFail();

        return view('patient.medical-records.index', compact('patient'));
    }

    /**
     * Store a new medical history record.
    {
        $user = Auth::user();

        if (!$user->hasRole('doctor')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'patient_profile_id' => 'required|exists:patient_profiles,id',
            'category' => 'required|in:allergy,chronic_condition,surgery,family_history,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'nullable|in:low,medium,high,critical',
            'onset_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['recorded_by'] = $user->id;
        $validated['recorded_at'] = now();

        MedicalHistory::create($validated);

        return back()->with('success', 'Medical history record added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Accessible by Doctors.
     */
    public function destroy(MedicalHistory $medicalHistory)
    {
        $user = Auth::user();

        if (!$user->hasRole('doctor')) {
            abort(403);
        }

        // Optional: Check if doctor has relationship with patient?
        // For now, allow any doctor to delete (or restricted to who created it? No, shared history is better).

        $medicalHistory->delete();

        return back()->with('success', 'Medical history record removed.');
    }
}
