<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('doctor')) {
            $prescriptions = Prescription::where('doctor_profile_id', $user->doctorProfile->id)
                ->with('patient.user')
                ->latest()
                ->paginate(10);
            return view('doctor.prescriptions.index', compact('prescriptions'));
        } elseif ($user->hasRole('patient')) {
            $prescriptions = Prescription::where('patient_profile_id', $user->patientProfile->id)
                ->with('doctor.user')
                ->latest()
                ->paginate(10);
            return view('patient.prescriptions.index', compact('prescriptions'));
        } else {
            abort(403);
        }
    }

    public function create(Request $request)
    {
        $patientId = $request->query('patient_id');
        $patient = null;
        if ($patientId) {
            $patient = PatientProfile::with('user')->findOrFail($patientId);
        }

        $patients = PatientProfile::with('user')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        return view('doctor.prescriptions.create', compact('patient', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_profile_id' => 'required|exists:patient_profiles,id',
            'items' => 'required|array|min:1',
            'items.*.medication_name' => 'required|string|max:255',
            'items.*.dosage' => 'required|string|max:100',
            'items.*.frequency' => 'required|string|max:100',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $doctor = Auth::user()->doctorProfile;

        $prescription = Prescription::create([
            'patient_profile_id' => $request->patient_profile_id,
            'doctor_profile_id' => $doctor->id,
            'issued_date' => now(),
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $prescription->items()->create($item);
        }

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $user = Auth::user();

        // Authorization check
        if ($user->hasRole('doctor')) {
            if ($prescription->doctor_profile_id !== $user->doctorProfile->id) {
                abort(403);
            }
        } elseif ($user->hasRole('patient')) {
            if ($prescription->patient_profile_id !== $user->patientProfile->id) {
                abort(403);
            }
        } else {
            // Admin or other roles?
            // abort(403);
        }

        $prescription->load(['items', 'doctor.user', 'patient.user']);

        return view('prescriptions.show', compact('prescription'));
    }
}
