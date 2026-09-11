<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasAnyRole(['super_admin', 'clinic_admin'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
        }

        if ($user->hasRole('pharmacist')) {
            return redirect()->route('pharmacist.dashboard');
        }

        if ($user->hasRole('lab_technician')) {
            return redirect()->route('lab.dashboard');
        }

        // Default or Patient
        return redirect()->route('patient.dashboard');
    }

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function doctor()
    {
        return view('dashboard.doctor');
    }

    public function receptionist()
    {
        return view('dashboard.receptionist');
    }

    public function pharmacist()
    {
        return view('dashboard.pharmacist');
    }

    public function lab()
    {
        return view('dashboard.lab');
    }

    public function patient()
    {
        return view('dashboard.patient');
    }

    public function manageLocations(Request $request)
    {
        $locations = Location::get($request);

        return view('dashboard.manage-locations', compact('locations'));
    }
}
