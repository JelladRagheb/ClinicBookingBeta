<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Sometimes developers pass roles as a single string separated by pipes or commas
        if (count($roles) === 1 && str_contains($roles[0], '|')) {
            $allowedRoles = explode('|', $roles[0]);
        } elseif (count($roles) === 1 && str_contains($roles[0], ',')) {
            $allowedRoles = explode(',', $roles[0]);
        } else {
            $allowedRoles = $roles;
        }

        if (!$request->user()->hasAnyRole($allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Required role: ' . implode(' or ', $allowedRoles),
                    'required_roles' => $allowedRoles,
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
