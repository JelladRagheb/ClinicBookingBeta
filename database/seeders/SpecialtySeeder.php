<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            [
                'name' => [
                    'fr' => 'Cardiologie',
                    'ar' => 'أمراض القلب',
                    'en' => 'Cardiology',
                ],
                'slug' => 'cardiology',
                'description' => [
                    'fr' => 'Spécialité médicale qui traite les maladies du cœur et des vaisseaux sanguins',
                    'ar' => 'التخصص الطبي الذي يعالج أمراض القلب والأوعية الدموية',
                    'en' => 'Medical specialty dealing with heart and blood vessel diseases',
                ],
                'icon' => 'heart',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Pédiatrie',
                    'ar' => 'طب الأطفال',
                    'en' => 'Pediatrics',
                ],
                'slug' => 'pediatrics',
                'description' => [
                    'fr' => 'Spécialité médicale consacrée aux soins des enfants',
                    'ar' => 'التخصص الطبي المخصص لرعاية الأطفال',
                    'en' => 'Medical specialty dedicated to child healthcare',
                ],
                'icon' => 'baby',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Médecine Générale',
                    'ar' => 'الطب العام',
                    'en' => 'General Medicine',
                ],
                'slug' => 'general-medicine',
                'description' => [
                    'fr' => 'Pratique médicale générale pour tous les âges',
                    'ar' => 'الممارسة الطبية العامة لجميع الأعمار',
                    'en' => 'General medical practice for all ages',
                ],
                'icon' => 'stethoscope',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Dermatologie',
                    'ar' => 'الأمراض الجلدية',
                    'en' => 'Dermatology',
                ],
                'slug' => 'dermatology',
                'description' => [
                    'fr' => 'Spécialité médicale traitant les maladies de la peau',
                    'ar' => 'التخصص الطبي الذي يعالج أمراض الجلد',
                    'en' => 'Medical specialty treating skin diseases',
                ],
                'icon' => 'skin',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Orthopédie',
                    'ar' => 'جراحة العظام',
                    'en' => 'Orthopedics',
                ],
                'slug' => 'orthopedics',
                'description' => [
                    'fr' => 'Spécialité chirurgicale traitant les troubles musculo-squelettiques',
                    'ar' => 'التخصص الجراحي الذي يعالج اضطرابات الجهاز العضلي الهيكلي',
                    'en' => 'Surgical specialty treating musculoskeletal disorders',
                ],
                'icon' => 'bone',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Gynécologie',
                    'ar' => 'أمراض النساء',
                    'en' => 'Gynecology',
                ],
                'slug' => 'gynecology',
                'description' => [
                    'fr' => 'Spécialité médicale traitant la santé reproductive féminine',
                    'ar' => 'التخصص الطبي الذي يعالج الصحة الإنجابية للمرأة',
                    'en' => 'Medical specialty treating female reproductive health',
                ],
                'icon' => 'female',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Ophtalmologie',
                    'ar' => 'طب العيون',
                    'en' => 'Ophthalmology',
                ],
                'slug' => 'ophthalmology',
                'description' => [
                    'fr' => 'Spécialité médicale traitant les maladies des yeux',
                    'ar' => 'التخصص الطبي الذي يعالج أمراض العيون',
                    'en' => 'Medical specialty treating eye diseases',
                ],
                'icon' => 'eye',
                'is_active' => true,
            ],
            [
                'name' => [
                    'fr' => 'Dentisterie',
                    'ar' => 'طب الأسنان',
                    'en' => 'Dentistry',
                ],
                'slug' => 'dentistry',
                'description' => [
                    'fr' => 'Spécialité médicale traitant les dents et la cavité buccale',
                    'ar' => 'التخصص الطبي الذي يعالج الأسنان وتجويف الفم',
                    'en' => 'Medical specialty treating teeth and oral cavity',
                ],
                'icon' => 'tooth',
                'is_active' => true,
            ],
        ];

        foreach ($specialties as $specialtyData) {
            Specialty::updateOrCreate(
                ['slug' => $specialtyData['slug']],
                $specialtyData
            );
        }

        $this->command->info('✅ Specialties seeded successfully!');
    }
}
