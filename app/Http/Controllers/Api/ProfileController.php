<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'preferred_language' => 'sometimes|in:fr,ar,en',
            'avatar' => 'sometimes|image|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['first_name', 'last_name', 'phone', 'preferred_language']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'avatar_url' => $user->avatar_url,
                'preferred_language' => $user->preferred_language,
            ],
        ], 200);
    }

    /**
     * Update patient profile
     */
    public function updatePatientProfile(Request $request)
    {
        $user = $request->user();

        if (!$user->isPatient()) {
            return response()->json([
                'message' => 'Only patients can update patient profile',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
            'blood_type' => 'sometimes|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height_cm' => 'sometimes|numeric|min:0|max:300',
            'weight_kg' => 'sometimes|numeric|min:0|max:500',
            'emergency_contact_name' => 'sometimes|string|max:255',
            'emergency_contact_phone' => 'sometimes|string|max:20',
            'emergency_contact_relation' => 'sometimes|string|max:100',
            'insurance_provider' => 'sometimes|string|max:255',
            'insurance_number' => 'sometimes|string|max:100',
            'insurance_expiry' => 'sometimes|date|after:today',
            'preferred_location_id' => 'sometimes|exists:locations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->patientProfile->update($request->all());

        return response()->json([
            'message' => 'Patient profile updated successfully',
            'patient_profile' => $user->patientProfile,
        ], 200);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return response()->json([
            'message' => 'Avatar deleted successfully',
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $response = response()->json([
            'user' => [
                'uuid' => $user->uuid,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'avatar_url' => $user->avatar_url,
                'preferred_language' => $user->preferred_language,
            ],
        ], 200);
        return view('layouts.Profile', compact('user'));
    }
}
