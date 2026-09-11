<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permissions  Comma-separated permission names
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $requiredPermissions = explode(',', $permissions);
        $userPermissions = $this->getUserPermissions($request->user());

        foreach ($requiredPermissions as $permission) {
            if (!in_array(trim($permission), $userPermissions)) {
                return response()->json([
                    'message' => 'Unauthorized. Missing permission: ' . $permission,
                    'required_permissions' => $requiredPermissions,
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Get all permissions for the user from their roles
     */
    private function getUserPermissions($user): array
    {
        $permissions = [];

        foreach ($user->roles as $role) {
            if (is_array($role->permissions)) {
                $permissions = array_merge($permissions, $role->permissions);
            }
        }

        return array_unique($permissions);
    }
}
