<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine if the user can view any appointments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'clinic_admin', 'doctor', 'receptionist']);
    }

    /**
     * Determine if the user can view the appointment.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Super admin and clinic admin can view all
        if ($user->hasAnyRole(['super_admin', 'clinic_admin'])) {
            return true;
        }

        // Doctor can view their own appointments
        if ($user->isDoctor() && $appointment->doctor_profile_id === $user->doctorProfile?->id) {
            return true;
        }

        // Patient can view their own appointments
        if ($user->isPatient() && $appointment->patient_id === $user->id) {
            return true;
        }

        // Receptionist can view appointments at their location
        if ($user->hasRole('receptionist')) {
            return true; // Can be refined to check location
        }

        return false;
    }

    /**
     * Determine if the user can create appointments.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['patient', 'receptionist', 'clinic_admin']);
    }

    /**
     * Determine if the user can update the appointment.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Only receptionist and admin can update
        if ($user->hasAnyRole(['receptionist', 'clinic_admin', 'super_admin'])) {
            return true;
        }

        // Doctor can update their own appointments (e.g., check-in, complete)
        if ($user->isDoctor() && $appointment->doctor_profile_id === $user->doctorProfile?->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can cancel the appointment.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        // Check if appointment can be cancelled (time-based)
        if (!$appointment->canCancel()) {
            return false;
        }

        // Patient can cancel their own appointments
        if ($user->isPatient() && $appointment->patient_id === $user->id) {
            return true;
        }

        // Receptionist, doctor, and admin can cancel
        if ($user->hasAnyRole(['receptionist', 'doctor', 'clinic_admin', 'super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can reschedule the appointment.
     */
    public function reschedule(User $user, Appointment $appointment): bool
    {
        // Check if appointment can be rescheduled (time-based)
        if (!$appointment->canReschedule()) {
            return false;
        }

        // Patient can reschedule their own appointments
        if ($user->isPatient() && $appointment->patient_id === $user->id) {
            return true;
        }

        // Receptionist and admin can reschedule
        if ($user->hasAnyRole(['receptionist', 'clinic_admin', 'super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the appointment.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Only admin can permanently delete
        return $user->hasAnyRole(['clinic_admin', 'super_admin']);
    }
}
