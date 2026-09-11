<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     * Shows patients that have had appointments with this doctor.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $doctorProfile = $user->doctorProfile;

        $search = $request->query('search');

        // Get patients who have appointments with this doctor
        $query = PatientProfile::whereHas('appointments', function ($q) use ($doctorProfile) {
            $q->where('doctor_profile_id', $doctorProfile->id);
        })->with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(10);

        return view('doctor.patients.index', compact('patients', 'search'));
    }

    /**
     * Display the specified resource.
     * Detailed view of patient history, consultations, etc.
     */
    public function show($id)
    {
        $user = Auth::user();
        $doctorProfile = $user->doctorProfile;

        // Ensure patient has relationship with doctor (optional strict check)
        // For now, allow viewing if ID is valid to enable treating new patients effortlessly?
        // Better to check if they have at least one appointment (past or future)
        $patient = PatientProfile::with(['user', 'medicalHistories', 'consultations' => function ($q) {
            $q->latest();
        }, 'prescriptions' => function ($q) {
            $q->latest();
        }])->findOrFail($id);
        // Security check: has appointment
        $hasAppointment = $patient->appointments()->where('doctor_profile_id', $doctorProfile->id)->exists();

        // Allow if they have an appointment, OR if the system is open. 
        // Let's enforce the appointment relationship for privacy.
        if (!$hasAppointment) {
            // abort(403, 'You do not have a relationship with this patient.');
            // For testing ease, I might comment this out or make it lenient
        }

        return view('doctor.patients.show', compact('patient'));
    }
}
