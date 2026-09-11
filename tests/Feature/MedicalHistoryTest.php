<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\DoctorProfile;
use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_doctor_can_view_patient_details()
    {
        // Doctor
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC123']);

        // Patient
        $patient = User::factory()->create();
        $patient->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        // Appointment to establish relationship
        Appointment::create([
            'patient_profile_id' => $patientProfile->id,
            'doctor_profile_id' => $doctorProfile->id,
            'appointment_date' => now()->addDay(),
            'status' => 'scheduled',
            'appointment_type_id' => 1 // Assuming 1 exists or is optional? Factory might be safer but simplistic here.
        ]);

        $response = $this->actingAs($doctor)->get(route('doctor.patients.show', $patientProfile->id));

        $response->assertStatus(200);
        $response->assertSee($patient->full_name);
    }

    public function test_doctor_can_add_medical_history()
    {
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        // $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id]); 

        $patient = User::factory()->create();
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        $response = $this->actingAs($doctor)->post(route('medical-history.store'), [
            'patient_profile_id' => $patientProfile->id,
            'category' => 'allergy',
            'title' => 'Peanuts',
            'description' => 'Severe reaction',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('medical_histories', [
            'patient_profile_id' => $patientProfile->id,
            'title' => 'Peanuts',
            'category' => 'allergy'
        ]);
    }

    public function test_doctor_can_create_consultation()
    {
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC1']);

        $patient = User::factory()->create();
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        $appointment = Appointment::create([
            'patient_profile_id' => $patientProfile->id,
            'doctor_profile_id' => $doctorProfile->id,
            'appointment_date' => now(),
            'status' => 'scheduled',
            // 'appointment_type_id' => ... 
        ]);

        $response = $this->actingAs($doctor)->post(route('consultations.store'), [
            'appointment_id' => $appointment->id,
            'patient_profile_id' => $patientProfile->id,
            'chief_complaint' => 'Headache',
            'diagnosis' => 'Migraine',
            'treatment_plan' => 'Rest',
            'follow_up_required' => true,
        ]);

        $response->assertRedirect(route('doctor.dashboard'));
        $this->assertDatabaseHas('consultations', [
            'appointment_id' => $appointment->id,
            'diagnosis' => 'Migraine'
        ]);
    }

    public function test_patient_can_view_own_records()
    {
        $patient = User::factory()->create();
        $patient->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        // Add history
        MedicalHistory::create([
            'patient_profile_id' => $patientProfile->id,
            'recorded_by' => $patient->id, // Self recorded or doctor? FK requires user id.
            'category' => 'surgery',
            'title' => 'Appendectomy',
            'recorded_at' => now(),
        ]);

        $response = $this->actingAs($patient)->get(route('medical-history.index'));

        $response->assertStatus(200);
        $response->assertSee('Appendectomy');
    }
}
