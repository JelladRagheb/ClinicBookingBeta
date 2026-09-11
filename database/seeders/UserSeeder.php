<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Location;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles and locations
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $clinicAdminRole = Role::where('name', 'clinic_admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $receptionistRole = Role::where('name', 'receptionist')->first();
        $patientRole = Role::where('name', 'patient')->first();

        $location1 = Location::where('code', 'CLINIC-TUN-01')->first();
        $location2 = Location::where('code', 'CLINIC-SFX-01')->first();

        $cardiology = Specialty::where('slug', 'cardiology')->first();
        $pediatrics = Specialty::where('slug', 'pediatrics')->first();

        // 1. Super Admin
        $superAdmin = User::create([
            'email' => 'admin@clinicbooking.tn',
            'phone' => '+216 70 000 001',
            'password' => Hash::make('password'),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'preferred_language' => 'fr',
            'is_active' => true,
        ]);
        $superAdmin->roles()->attach($superAdminRole->id);

        // 2. Clinic Admin (Location 1)
        $clinicAdmin = User::create([
            'email' => 'admin.tunis@clinicbooking.tn',
            'phone' => '+216 70 000 002',
            'password' => Hash::make('password'),
            'first_name' => 'Ahmed',
            'last_name' => 'Ben Ali',
            'preferred_language' => 'fr',
            'is_active' => true,
        ]);
        $clinicAdmin->roles()->attach($clinicAdminRole->id, ['location_id' => $location1->id]);

        // 3. Doctor 1 - Cardiologist
        $doctor1 = User::create([
            'email' => 'dr.trabelsi@clinicbooking.tn',
            'phone' => '+216 70 000 003',
            'password' => Hash::make('password'),
            'first_name' => 'Mohamed',
            'last_name' => 'Trabelsi',
            'preferred_language' => 'fr',
            'is_active' => true,
        ]);
        $doctor1->roles()->attach($doctorRole->id, ['location_id' => $location1->id]);

        $doctorProfile1 = DoctorProfile::create([
            'user_id' => $doctor1->id,
            'license_number' => 'TN-DOC-12345',
            'bio' => [
                'fr' => 'Cardiologue expérimenté avec 15 ans de pratique',
                'ar' => 'طبيب قلب ذو خبرة 15 عامًا',
                'en' => 'Experienced cardiologist with 15 years of practice',
            ],
            'education' => [
                'Doctorat en Médecine - Faculté de Médecine de Tunis',
                'Spécialisation en Cardiologie - Hôpital La Rabta',
            ],
            'experience_years' => 15,
            'consultation_fee' => 80.00,
            'cancellation_hours' => 24,
            'reschedule_hours' => 12,
            'accepts_walk_ins' => true,
            'is_available' => true,
        ]);
        $doctorProfile1->specialties()->attach($cardiology->id, ['is_primary' => true]);
        $doctorProfile1->locations()->attach($location1->id, ['is_primary' => true]);

        $faker = \Faker\Factory::create('fr_FR');

        // Create 4 more doctors to make it 5 total
        for ($i = 2; $i <= 5; $i++) {
            $isEven = ($i % 2 == 0);
            $docLocation = $isEven ? $location2 : $location1;
            
            $doc = User::create([
                'email' => "doctor{$i}@clinicbooking.tn",
                'phone' => '+216 70 000 ' . str_pad($i + 100, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'preferred_language' => 'fr',
                'is_active' => true,
            ]);
            $doc->roles()->attach($doctorRole->id, ['location_id' => $docLocation->id]);

            $docProfile = DoctorProfile::create([
                'user_id' => $doc->id,
                'license_number' => 'TN-DOC-' . $faker->randomNumber(5, true),
                'bio' => [
                    'fr' => 'Médecin spécialiste expérimenté.',
                    'ar' => 'طبيب متخصص ذو خبرة.',
                    'en' => 'Experienced specialist doctor.',
                ],
                'education' => [
                    'Doctorat en Médecine - Faculté de Médecine',
                ],
                'experience_years' => $faker->numberBetween(3, 20),
                'consultation_fee' => $faker->randomElement([50, 60, 70, 80, 100]),
                'cancellation_hours' => 24,
                'reschedule_hours' => 12,
                'accepts_walk_ins' => $faker->boolean(),
                'is_available' => true,
            ]);
            $docProfile->specialties()->attach($isEven ? $pediatrics->id : $cardiology->id, ['is_primary' => true]);
            $docProfile->locations()->attach($docLocation->id, ['is_primary' => true]);
        }

        // 5. Receptionist
        $receptionist = User::create([
            'email' => 'reception@clinicbooking.tn',
            'phone' => '+216 70 000 005',
            'password' => Hash::make('password'),
            'first_name' => 'Fatima',
            'last_name' => 'Gharbi',
            'preferred_language' => 'fr',
            'is_active' => true,
        ]);
        $receptionist->roles()->attach($receptionistRole->id, ['location_id' => $location1->id]);

        // 6. Sample Patient 1 (user_id = 6)
        $patient = User::create([
            'email' => 'patient@example.com',
            'phone' => '+216 70 000 006',
            'password' => Hash::make('password'),
            'first_name' => 'Youssef',
            'last_name' => 'Bouazizi',
            'preferred_language' => 'fr',
            'is_active' => true,
        ]);
        $patient->roles()->attach($patientRole->id);

        PatientProfile::create([
            'user_id' => $patient->id,
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'blood_type' => 'A+',
            'height_cm' => 175.00,
            'weight_kg' => 75.00,
            'emergency_contact_name' => 'Amira Bouazizi',
            'emergency_contact_phone' => '+216 70 111 222',
            'emergency_contact_relation' => 'Épouse',
            'insurance_provider' => 'CNAM',
            'insurance_number' => 'TN-INS-123456',
            'insurance_expiry' => '2025-12-31',
            'preferred_location_id' => $location1->id,
        ]);

        // Create 9 more patients to make it 10 total
        for ($i = 2; $i <= 10; $i++) {
            $pat = User::create([
                'email' => "patient{$i}@example.com",
                'phone' => '+216 70 000 ' . str_pad($i + 200, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'preferred_language' => $faker->randomElement(['fr', 'ar']),
                'is_active' => true,
            ]);
            $pat->roles()->attach($patientRole->id);

            PatientProfile::create([
                'user_id' => $pat->id,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female']),
                'blood_type' => $faker->randomElement(['A+', 'O+', 'B+', 'AB+', 'A-', 'O-']),
                'height_cm' => $faker->numberBetween(150, 190),
                'weight_kg' => $faker->numberBetween(50, 100),
                'insurance_provider' => $faker->randomElement(['CNAM', 'COMAR', 'STAR', 'GAT', 'MAGHREBIA']),
                'preferred_location_id' => ($i % 2 == 0) ? $location2->id : $location1->id,
            ]);
        }

        $this->command->info('✅ Sample users seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('Super Admin: admin@clinicbooking.tn / password');
        $this->command->info('Clinic Admin: admin.tunis@clinicbooking.tn / password');
        $this->command->info('Doctor 1: dr.trabelsi@clinicbooking.tn / password');
        $this->command->info('Doctor 2: dr.mansour@clinicbooking.tn / password');
        $this->command->info('Receptionist: reception@clinicbooking.tn / password');
        $this->command->info('Patient: patient@example.com / password');
    }
}
