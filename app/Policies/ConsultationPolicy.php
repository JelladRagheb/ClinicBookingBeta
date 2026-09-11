<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    /**
     * Determine if the user can view any consultations.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'clinic_admin', 'doctor']);
    }

    /**
     * Determine if the user can view the consultation.
     */
    public function view(User $user, Consultation $consultation): bool
    {
        // Super admin can view all
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Doctor can view their own consultations
        if ($user->isDoctor() && $consultation->doctor_profile_id === $user->doctorProfile?->id) {
            return true;
        }

        // Patient can view their own consultations
        if ($user->isPatient() && $consultation->patient_profile_id === $user->patientProfile?->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create consultations.
     */
    public function create(User $user): bool
    {
        // Only doctors can create consultations
        return $user->isDoctor();
    }

    /**
     * Determine if the user can update the consultation.
     */
    public function update(User $user, Consultation $consultation): bool
    {
        // Only the doctor who created it can update
        if ($user->isDoctor() && $consultation->doctor_profile_id === $user->doctorProfile?->id) {
            return true;
        }

        // Super admin can update
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the consultation.
     */
    public function delete(User $user, Consultation $consultation): bool
    {
        // Only super admin can delete consultations (audit trail)
        return $user->hasRole('super_admin');
    }
}
