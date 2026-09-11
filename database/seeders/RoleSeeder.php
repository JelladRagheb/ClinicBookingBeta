<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => [
                    'fr' => 'Super Administrateur',
                    'ar' => 'مدير عام',
                    'en' => 'Super Administrator',
                ],
                'description' => [
                    'fr' => 'Accès complet au système',
                    'ar' => 'وصول كامل للنظام',
                    'en' => 'Full system access',
                ],
                'permissions' => [
                    'manage_system',
                    'manage_locations',
                    'manage_users',
                    'manage_roles',
                    'view_all_appointments',
                    'view_all_patients',
                    'view_reports',
                    'manage_settings',
                ],
            ],
            [
                'name' => 'clinic_admin',
                'display_name' => [
                    'fr' => 'Administrateur de Clinique',
                    'ar' => 'مدير العيادة',
                    'en' => 'Clinic Administrator',
                ],
                'description' => [
                    'fr' => 'Gestion d\'une clinique spécifique',
                    'ar' => 'إدارة عيادة محددة',
                    'en' => 'Manage specific clinic',
                ],
                'permissions' => [
                    'manage_location_users',
                    'view_location_appointments',
                    'view_location_patients',
                    'view_location_reports',
                    'manage_location_settings',
                    'manage_doctors',
                    'manage_schedules',
                ],
            ],
            [
                'name' => 'doctor',
                'display_name' => [
                    'fr' => 'Médecin',
                    'ar' => 'طبيب',
                    'en' => 'Doctor',
                ],
                'description' => [
                    'fr' => 'Médecin praticien',
                    'ar' => 'طبيب ممارس',
                    'en' => 'Medical practitioner',
                ],
                'permissions' => [
                    'view_own_appointments',
                    'manage_own_schedule',
                    'manage_own_time_off',
                    'view_patient_records',
                    'create_consultations',
                    'create_prescriptions',
                    'view_medical_history',
                    'record_vital_signs',
                    'update_consultation',
                ],
            ],
            [
                'name' => 'receptionist',
                'display_name' => [
                    'fr' => 'Réceptionniste',
                    'ar' => 'موظف استقبال',
                    'en' => 'Receptionist',
                ],
                'description' => [
                    'fr' => 'Personnel de réception',
                    'ar' => 'موظف الاستقبال',
                    'en' => 'Front desk staff',
                ],
                'permissions' => [
                    'view_appointments',
                    'create_appointments',
                    'update_appointments',
                    'cancel_appointments',
                    'check_in_patients',
                    'view_patient_basic_info',
                    'create_patients',
                    'process_payments',
                    'view_doctor_schedules',
                ],
            ],
            [
                'name' => 'patient',
                'display_name' => [
                    'fr' => 'Patient',
                    'ar' => 'مريض',
                    'en' => 'Patient',
                ],
                'description' => [
                    'fr' => 'Utilisateur patient',
                    'ar' => 'مستخدم مريض',
                    'en' => 'Patient user',
                ],
                'permissions' => [
                    'view_own_appointments',
                    'create_own_appointments',
                    'cancel_own_appointments',
                    'reschedule_own_appointments',
                    'view_own_medical_records',
                    'view_own_prescriptions',
                    'view_own_payments',
                    'update_own_profile',
                    'view_doctors',
                    'view_available_slots',
                ],
            ],
            [
                'name' => 'pharmacist',
                'display_name' => [
                    'fr' => 'Pharmacien',
                    'ar' => 'صيدلي',
                    'en' => 'Pharmacist',
                ],
                'description' => [
                    'fr' => 'Personnel de pharmacie',
                    'ar' => 'موظف الصيدلية',
                    'en' => 'Pharmacy staff',
                ],
                'permissions' => [
                    'view_prescriptions',
                    'fill_prescriptions',
                    'view_prescription_history',
                    'update_prescription_status',
                ],
            ],
            [
                'name' => 'lab_technician',
                'display_name' => [
                    'fr' => 'Technicien de Laboratoire',
                    'ar' => 'فني مختبر',
                    'en' => 'Lab Technician',
                ],
                'description' => [
                    'fr' => 'Personnel de laboratoire',
                    'ar' => 'موظف المختبر',
                    'en' => 'Laboratory staff',
                ],
                'permissions' => [
                    'view_lab_orders',
                    'create_lab_results',
                    'update_lab_results',
                    'view_patient_lab_history',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('✅ Roles seeded successfully!');
    }
}
