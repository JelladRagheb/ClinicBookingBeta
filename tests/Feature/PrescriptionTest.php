<?php

namespace Tests\Feature;

use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrescriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles if not using a global seeder in test
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_doctor_can_create_prescription()
    {
        // Doctor
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC123']);

        // Patient
        $patient = User::factory()->create();
        $patient->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        $response = $this->actingAs($doctor)->post(route('prescriptions.store'), [
            'patient_profile_id' => $patientProfile->id,
            'notes' => 'Take rest',
            'items' => [
                [
                    'medication_name' => 'Aspirin',
                    'dosage' => '500mg',
                    'frequency' => 'Once daily',
                    'duration_days' => 5,
                    'quantity' => 10
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('prescriptions', [
            'doctor_profile_id' => $doctorProfile->id,
            'patient_profile_id' => $patientProfile->id,
            'notes' => 'Take rest'
        ]);
        $this->assertDatabaseHas('prescription_items', [
            'medication_name' => 'Aspirin'
        ]);
    }

    public function test_patient_can_view_own_prescription()
    {
        // Doctor
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC123']);

        // Patient
        $patient = User::factory()->create();
        $patient->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile = PatientProfile::create(['user_id' => $patient->id]);

        // Create Prescription
        $prescription = \App\Models\Prescription::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'prescription_number' => 'RX-TEST-001',
            'doctor_profile_id' => $doctorProfile->id,
            'patient_profile_id' => $patientProfile->id,
            'issued_date' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($patient)->get(route('prescriptions.show', $prescription));
        $response->assertStatus(200);
        $response->assertSee('RX-TEST-001');
    }

    public function test_patient_cannot_view_others_prescription()
    {
        // Doctor
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $doctorProfile = DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC123']);

        // Patient 1 (Owner)
        $patient1 = User::factory()->create();
        $patient1->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile1 = PatientProfile::create(['user_id' => $patient1->id]);

        // Patient 2 (Attacker)
        $patient2 = User::factory()->create();
        $patient2->roles()->attach(Role::where('name', 'patient')->first());
        $patientProfile2 = PatientProfile::create(['user_id' => $patient2->id]);

        // Create Prescription for Patient 1
        $prescription = \App\Models\Prescription::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'prescription_number' => 'RX-TEST-002',
            'doctor_profile_id' => $doctorProfile->id,
            'patient_profile_id' => $patientProfile1->id,
            'issued_date' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($patient2)->get(route('prescriptions.show', $prescription));
        $response->assertStatus(403);
    }
}
