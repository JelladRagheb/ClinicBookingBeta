<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\DoctorProfile;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor1 = DoctorProfile::first();
        $doctor2 = DoctorProfile::skip(1)->first();
        
        $patients = User::whereHas('roles', function ($q) {
            $q->where('name', 'patient');
        })->get();

        $location1 = Location::first();
        $appointmentType = AppointmentType::first();

        if (!$doctor1 || $patients->isEmpty() || !$location1 || !$appointmentType) {
            $this->command->info('Please run UserSeeder, LocationSeeder, and AppointmentTypeSeeder first.');
            return;
        }

        $now = Carbon::now();

        if (isset($patients[0])) {
            Appointment::create([
                'patient_id' => $patients[0]->id,
                'doctor_profile_id' => $doctor1->id,
                'location_id' => $location1->id,
                'appointment_type_id' => $appointmentType->id,
                'scheduled_date' => $now->copy()->format('Y-m-d'),
                'scheduled_time' => '10:00:00',
                'duration_minutes' => 30,
                'status' => 'scheduled',
                'is_walk_in' => false,
                'is_recurring' => false,
            ]);

            Appointment::create([
                'patient_id' => $patients[0]->id,
                'doctor_profile_id' => $doctor1->id,
                'location_id' => $location1->id,
                'appointment_type_id' => $appointmentType->id,
                'scheduled_date' => $now->copy()->addDay()->format('Y-m-d'),
                'scheduled_time' => '14:00:00',
                'duration_minutes' => 45,
                'status' => 'scheduled',
                'is_walk_in' => false,
                'is_recurring' => false,
            ]);
        }

        if (isset($patients[1])) {
            Appointment::create([
                'patient_id' => $patients[1]->id,
                'doctor_profile_id' => $doctor1->id,
                'location_id' => $location1->id,
                'appointment_type_id' => $appointmentType->id,
                'scheduled_date' => $now->copy()->format('Y-m-d'),
                'scheduled_time' => '11:00:00',
                'duration_minutes' => 30,
                'status' => 'confirmed',
                'is_walk_in' => true,
                'is_recurring' => false,
            ]);

            if ($doctor2) {
                Appointment::create([
                    'patient_id' => $patients[1]->id,
                    'doctor_profile_id' => $doctor2->id,
                    'location_id' => $location1->id,
                    'appointment_type_id' => $appointmentType->id,
                    'scheduled_date' => $now->copy()->addDays(2)->format('Y-m-d'),
                    'scheduled_time' => '15:30:00',
                    'duration_minutes' => 30,
                    'status' => 'scheduled',
                    'is_walk_in' => false,
                    'is_recurring' => false,
                ]);
            }
        }

        if (isset($patients[2]) && $doctor2) {
            Appointment::create([
                'patient_id' => $patients[2]->id,
                'doctor_profile_id' => $doctor2->id,
                'location_id' => $location1->id,
                'appointment_type_id' => $appointmentType->id,
                'scheduled_date' => $now->copy()->addDays(2)->format('Y-m-d'),
                'scheduled_time' => '09:00:00',
                'duration_minutes' => 30,
                'status' => 'scheduled',
                'is_walk_in' => false,
                'is_recurring' => false,
            ]);

            Appointment::create([
                'patient_id' => $patients[2]->id,
                'doctor_profile_id' => $doctor1->id,
                'location_id' => $location1->id,
                'appointment_type_id' => $appointmentType->id,
                'scheduled_date' => $now->copy()->subDays(1)->format('Y-m-d'),
                'scheduled_time' => '10:00:00',
                'duration_minutes' => 60,
                'status' => 'completed',
                'is_walk_in' => false,
                'is_recurring' => false,
            ]);
        }

        $this->command->info('✅ Sample appointments seeded successfully!');
    }
}
