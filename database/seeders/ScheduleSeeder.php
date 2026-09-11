<?php

namespace Database\Seeders;

use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor1 = DoctorProfile::first();
        $doctor2 = DoctorProfile::skip(1)->first();
        $patients = PatientProfile::all(); // Get all patients

        if (!$doctor1 || $patients->isEmpty()) {
            $this->command->info('Please run UserSeeder first.');
            return;
        }

        $now = Carbon::now();

        // Appointments for Patient 1
        if (isset($patients[0])) {
            $patient1 = $patients[0];
            Schedule::create([
                'doctor_profile_id' => $doctor1->id,
                'patient_profile_id' => $patient1->id,
                'title' => 'Routine Checkup - ' . $patient1->user->first_name,
                'start' => $now->copy()->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->setHour(10)->setMinute(30)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#3B82F6', // Blue
            ]);

            Schedule::create([
                'doctor_profile_id' => $doctor1->id,
                'patient_profile_id' => $patient1->id,
                'title' => 'Follow-up Consultation',
                'start' => $now->copy()->addDay()->setHour(14)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->addDay()->setHour(14)->setMinute(45)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#10B981', // Green
            ]);
        }

        // Appointments for Patient 2
        if (isset($patients[1])) {
            $patient2 = $patients[1];
            Schedule::create([
                'doctor_profile_id' => $doctor1->id,
                'patient_profile_id' => $patient2->id,
                'title' => 'Cardiac Screening - ' . $patient2->user->first_name,
                'start' => $now->copy()->setHour(11)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->setHour(12)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#EF4444', // Red
            ]);
            
            Schedule::create([
                'doctor_profile_id' => $doctor2->id,
                'patient_profile_id' => $patient2->id,
                'title' => 'General Consultation',
                'start' => $now->copy()->addDays(2)->setHour(15)->setMinute(30)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->addDays(2)->setHour(16)->setMinute(00)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#F59E0B', // Yellow
            ]);
        }

        // Appointments for Patient 3
        if (isset($patients[2]) && $doctor2) {
            $patient3 = $patients[2];
            Schedule::create([
                'doctor_profile_id' => $doctor2->id,
                'patient_profile_id' => $patient3->id,
                'title' => 'Pediatric Consultation - ' . $patient3->user->first_name,
                'start' => $now->copy()->addDays(2)->setHour(9)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->addDays(2)->setHour(9)->setMinute(30)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#8B5CF6', // Purple
            ]);
            
            Schedule::create([
                'doctor_profile_id' => $doctor1->id,
                'patient_profile_id' => $patient3->id,
                'title' => 'Echocardiogram',
                'start' => $now->copy()->subDays(1)->setHour(10)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'end' => $now->copy()->subDays(1)->setHour(11)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s'),
                'color' => '#EC4899', // Pink (Past appointment)
            ]);
        }

        $this->command->info('✅ Sample schedules seeded successfully!');
    }
}
