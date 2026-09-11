<?php

namespace App\Traits;

trait HasPermissions
{
    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getAllPermissions();
        return in_array($permission, $permissions);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        $userPermissions = $this->getAllPermissions();

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $userPermissions = $this->getAllPermissions();

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all permissions from user's roles
     */
    public function getAllPermissions(): array
    {
        $permissions = [];

        foreach ($this->roles as $role) {
            if (is_array($role->permissions)) {
                $permissions = array_merge($permissions, $role->permissions);
            }
        }

        return array_unique($permissions);
    }

    /**
     * Get permissions for a specific location
     */
    public function getPermissionsForLocation(int $locationId): array
    {
        $permissions = [];

        $rolesAtLocation = $this->roles()->wherePivot('location_id', $locationId)->get();

        foreach ($rolesAtLocation as $role) {
            if (is_array($role->permissions)) {
                $permissions = array_merge($permissions, $role->permissions);
            }
        }

        return array_unique($permissions);
    }

    /**
     * Check if user can perform action at specific location
     */
    public function canAtLocation(string $permission, int $locationId): bool
    {
        $permissions = $this->getPermissionsForLocation($locationId);
        return in_array($permission, $permissions);
    }
}
