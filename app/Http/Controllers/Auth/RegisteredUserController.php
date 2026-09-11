<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Models\User;
use App\Models\Role;
use App\Models\PatientProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'clinic_admin'])->get();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20', 'unique:' . User::class],
            'role' => ['required', 'string', 'exists:roles,name', 'not_in:super_admin,clinic_admin'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'preferred_language' => 'fr', // Default
            'is_active' => true,
        ]);

        // Assign Role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Create Profile based on Role
        if ($request->role === 'patient') {
            PatientProfile::create([
                'user_id' => $user->id,
            ]);
            $redirectRoute = 'patient.dashboard';
        } elseif ($request->role === 'doctor') {
            // Doctors might need approval, but creating profile for now
            DoctorProfile::create([
                'user_id' => $user->id,
                'license_number' => 'PENDING-' . uniqid(),
                'is_available' => false,
            ]);
            $redirectRoute = 'doctor.dashboard';
        } else {
            // Other roles redirect to dashboard base or specific if exists
            $redirectRoute = 'dashboard';
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route($redirectRoute, absolute: false));
    }
}
