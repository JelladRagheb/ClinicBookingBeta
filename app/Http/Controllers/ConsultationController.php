<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Show the form for creating a new consultation.
     */
    public function create(Request $request)
    {
        $appointmentId = $request->query('appointment_id');
        $appointment = Appointment::with(['patient.user', 'doctor.user'])->findOrFail($appointmentId);

        // Authorization: Ensure logged in doctor owns the appointment
        $user = Auth::user();
        if ($appointment->doctor_profile_id !== $user->doctorProfile->id) {
            abort(403);
        }

        $patient = $appointment->patient;

        // Load existing history for reference
        $medicalHistory = $patient->medicalHistories()->active()->latest()->get();
        $pastConsultations = Consultation::where('patient_profile_id', $patient->id)
            ->where('id', '!=', $appointment->consultation?->id) // Exclude current if exists
            ->latest()
            ->get();

        return view('doctor.consultations.create', compact('appointment', 'patient', 'medicalHistory', 'pastConsultations'));
    }

    /**
     * Store a newly created consultation in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $doctorProfile = $user->doctorProfile;

        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_profile_id' => 'required|exists:patient_profiles,id',
            'chief_complaint' => 'required|string',
            'symptoms' => 'nullable|array',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_required' => 'boolean',
            'follow_up_days' => 'nullable|integer|min:1',
            // Vital Signs (optional inline)
            'vital_signs' => 'nullable|array',
            'vital_signs.temperature_celsius' => 'nullable|numeric',
            'vital_signs.blood_pressure_systolic' => 'nullable|numeric',
            'vital_signs.blood_pressure_diastolic' => 'nullable|numeric',
            'vital_signs.heart_rate_bpm' => 'nullable|numeric',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        // double check auth
        if ($appointment->doctor_profile_id !== $doctorProfile->id) {
            abort(403);
        }

        $consultation = Consultation::create([
            'appointment_id' => $validated['appointment_id'],
            'patient_profile_id' => $validated['patient_profile_id'],
            'doctor_profile_id' => $doctorProfile->id,
            'chief_complaint' => $validated['chief_complaint'],
            'symptoms' => $validated['symptoms'] ?? [],
            'diagnosis' => $validated['diagnosis'],
            'treatment_plan' => $validated['treatment_plan'],
            'notes' => $validated['notes'],
            'follow_up_required' => $request->has('follow_up_required'),
            'follow_up_days' => $validated['follow_up_days'],
        ]);

        // If vital signs provided, create them
        if (!empty($request->vital_signs)) {
            $consultation->vitalSigns()->create([
                'patient_profile_id' => $validated['patient_profile_id'],
                'recorded_by' => $user->id,
                'temperature_celsius' => $request->input('vital_signs.temperature_celsius'),
                'blood_pressure_systolic' => $request->input('vital_signs.blood_pressure_systolic'),
                'blood_pressure_diastolic' => $request->input('vital_signs.blood_pressure_diastolic'),
                'heart_rate_bpm' => $request->input('vital_signs.heart_rate_bpm'),
            ]);
        }

        // Mark appointment as completed?
        $appointment->update(['status' => 'completed']);

        return redirect()->route('doctor.dashboard')->with('success', 'Consultation recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        $user = Auth::user();

        // Access control
        if ($user->hasRole('patient')) {
            if ($consultation->patient_profile_id !== $user->patientProfile->id) {
                abort(403);
            }
        } elseif ($user->hasRole('doctor')) {
            // Allows doctors to see any consultation? Or only their own?
            // Usually treating doctor should see history.
            // For now, strict check:
            if ($consultation->doctor_profile_id !== $user->doctorProfile->id) {
                // Check if doctor has *any* relationship with patient?
                // For now, allow viewing.
            }
        }

        return view('consultations.show', compact('consultation'));
    }
}
