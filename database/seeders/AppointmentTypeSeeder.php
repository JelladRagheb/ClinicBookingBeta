<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use Illuminate\Database\Seeder;

class AppointmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => [
                    'fr' => 'Consultation Générale',
                    'ar' => 'استشارة عامة',
                    'en' => 'General Consultation',
                ],
                'slug' => 'general-consultation',
                'description' => [
                    'fr' => 'Consultation médicale standard',
                    'ar' => 'استشارة طبية قياسية',
                    'en' => 'Standard medical consultation',
                ],
                'duration_minutes' => 30,
                'default_fee' => 50.00,
                'color' => '#3B82F6',
                'requires_preparation' => false,
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Suivi / Contrôle',
                    'ar' => 'متابعة',
                    'en' => 'Follow-up',
                ],
                'slug' => 'follow-up',
                'description' => [
                    'fr' => 'Consultation de suivi après traitement',
                    'ar' => 'استشارة متابعة بعد العلاج',
                    'en' => 'Follow-up consultation after treatment',
                ],
                'duration_minutes' => 20,
                'default_fee' => 30.00,
                'color' => '#10B981',
                'requires_preparation' => false,
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Consultation Urgente',
                    'ar' => 'استشارة طارئة',
                    'en' => 'Urgent Consultation',
                ],
                'slug' => 'urgent',
                'description' => [
                    'fr' => 'Consultation pour cas urgent',
                    'ar' => 'استشارة لحالة طارئة',
                    'en' => 'Consultation for urgent cases',
                ],
                'duration_minutes' => 45,
                'default_fee' => 80.00,
                'color' => '#EF4444',
                'requires_preparation' => false,
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Examen Complet',
                    'ar' => 'فحص شامل',
                    'en' => 'Complete Examination',
                ],
                'slug' => 'complete-exam',
                'description' => [
                    'fr' => 'Examen médical complet avec analyses',
                    'ar' => 'فحص طبي شامل مع التحاليل',
                    'en' => 'Complete medical examination with tests',
                ],
                'duration_minutes' => 60,
                'default_fee' => 100.00,
                'color' => '#8B5CF6',
                'requires_preparation' => true,
                'preparation_instructions' => [
                    'fr' => 'À jeun depuis 8 heures',
                    'ar' => 'صيام لمدة 8 ساعات',
                    'en' => 'Fasting for 8 hours',
                ],
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Vaccination',
                    'ar' => 'تطعيم',
                    'en' => 'Vaccination',
                ],
                'slug' => 'vaccination',
                'description' => [
                    'fr' => 'Administration de vaccins',
                    'ar' => 'إعطاء اللقاحات',
                    'en' => 'Vaccine administration',
                ],
                'duration_minutes' => 15,
                'default_fee' => 20.00,
                'color' => '#F59E0B',
                'requires_preparation' => false,
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Consultation Spécialisée',
                    'ar' => 'استشارة متخصصة',
                    'en' => 'Specialist Consultation',
                ],
                'slug' => 'specialist',
                'description' => [
                    'fr' => 'Consultation avec un médecin spécialiste',
                    'ar' => 'استشارة مع طبيب متخصص',
                    'en' => 'Consultation with specialist doctor',
                ],
                'duration_minutes' => 45,
                'default_fee' => 80.00,
                'color' => '#EC4899',
                'requires_preparation' => false,
                'is_active' => true,
            ],
        ];

        foreach ($types as $typeData) {
            AppointmentType::updateOrCreate(
                ['slug' => $typeData['slug']],
                $typeData
            );
        }

        $this->command->info('✅ Appointment types seeded successfully!');
    }
}
