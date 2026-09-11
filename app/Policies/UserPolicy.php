<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'clinic_admin']);
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can view all users
        return $user->hasAnyRole(['super_admin', 'clinic_admin']);
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'clinic_admin', 'receptionist']);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can update users
        return $user->hasAnyRole(['super_admin', 'clinic_admin']);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Only super admin can delete users
        return $user->hasRole('super_admin');
    }

    /**
     * Determine if the user can assign roles.
     */
    public function assignRole(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'clinic_admin']);
    }
}
