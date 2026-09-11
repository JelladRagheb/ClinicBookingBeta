<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user (patient)
     */
    public function register(Request $request)
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'clinic_admin'])->get();

        return view('auth.register', compact('roles'));
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => ['required', 'confirmed', Password::min(8)],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'preferred_language' => 'nullable|in:fr,ar,en',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create user
        $user = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'preferred_language' => $request->preferred_language ?? 'fr',
            'is_active' => true,
        ]);

        // Assign patient role
        $patientRole = Role::where('name', 'patient')->first();
        $user->roles()->attach($patientRole->id);

        // Create patient profile
        PatientProfile::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        // Create access token
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'preferred_language' => $user->preferred_language,
                'roles' => $user->roles->pluck('name'),
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is inactive. Please contact support.',
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create access token
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'avatar_url' => $user->avatar_url,
                'preferred_language' => $user->preferred_language,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions(),
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['roles', 'patientProfile', 'doctorProfile']);

        return response()->json([
            'user' => [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'avatar_url' => $user->avatar_url,
                'preferred_language' => $user->preferred_language,
                'email_verified_at' => $user->email_verified_at,
                'phone_verified_at' => $user->phone_verified_at,
                'last_login_at' => $user->last_login_at,
                'is_active' => $user->is_active,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'display_name' => $role->translated_name,
                        'location_id' => $role->pivot->location_id,
                    ];
                }),
                'permissions' => $user->getAllPermissions(),
                'patient_profile' => $user->patientProfile,
                'doctor_profile' => $user->doctorProfile,
            ],
        ], 200);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request)
    {
        $user = $request->user();

        // Revoke old token
        $request->user()->token()->revoke();

        // Create new token
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
}
