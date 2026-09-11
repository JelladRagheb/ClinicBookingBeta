<?php

use App\Http\Controllers\AdminDoctorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorPatientController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(Auth::user()->roles[0]->name);
    return view('welcome');
})->name('home');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global API Notifications
    Route::get('/api/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('/api/notifications/read/{id?}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    // Role Specific Dashboards
    Route::middleware(['role:super_admin,clinic_admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');
        Route::resource('locations', LocationController::class);
        Route::resource('specialties', SpecialtyController::class);
        Route::resource('admin/doctors', AdminDoctorController::class)->names('admin.doctors');
    });

    // Public/Shared Location Routes

    // Public Doctor Profile
    Route::get('/doctors/{id}', [DoctorProfileController::class, 'show'])->name('doctor.profile.show');

    // View Prescription (Shared access based on logic in controller)
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    // Medical Record Routes (Shared)
    Route::get('/medical-history', [MedicalHistoryController::class, 'index'])->name('medical-history.index');



    // Schedule Routes (Shared - Controller handles permissions)
    Route::controller(ScheduleController::class)->group(function () {
        Route::get('/calendar', 'index')->name('schedule.index'); // Renamed from fullcalendar for clarity, but kept name 'schedule.index'
        Route::post('/create-schedule', 'store')->name('schedule.store');
        Route::get('/events', 'getEvents')->name('schedule.events');
        Route::put('/schedule/{id}', 'update')->name('schedule.update');
        Route::delete('/schedule/{id}', 'deleteEvent')->name('schedule.deleteEvent');
        Route::get('/events/search', 'searchEvents')->name('schedule.search');
    });

    Route::middleware(['role:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DashboardController::class, 'doctor'])->name('doctor.dashboard');
        Route::get('/doctor/notifications', [NotificationController::class, 'index'])->name('doctor.notifications');

        // Patient Management
        Route::get('/doctor/patients', [DoctorPatientController::class, 'index'])->name('doctor.patients.index');
        Route::get('/doctor/patients/{id}', [DoctorPatientController::class, 'show'])->name('doctor.patients.show');

        // Consultation Routes
        Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
        Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
        Route::get('/consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');

        // Medical History (Doctor Add/Delete)
        Route::post('/medical-history', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
        Route::delete('/medical-history/{medicalHistory}', [MedicalHistoryController::class, 'destroy'])->name('medical-history.destroy');

        // Doctor Profile
        Route::get('/doctor/profile', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
        Route::put('/doctor/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
        // Route::get('/doctor/schedule', [DoctorScheduleController::class, 'index'])->name('doctor.schedule.index');
        // Route::post('/doctor/schedule', [DoctorScheduleController::class, 'store'])->name('doctor.schedule.store');
        // Route::put('/doctor/schedule/{id}', [DoctorScheduleController::class, 'update'])->name('doctor.schedule.update');
        // Route::delete('/doctor/schedule/{id}', [DoctorScheduleController::class, 'destroy'])->name('doctor.schedule.destroy');
        // Prescriptions (Doctor Create)
        Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    });

    // View Prescription (Shared access based on logic in controller)
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');


    Route::middleware(['role:receptionist'])->group(function () {
        Route::get('/receptionist/dashboard', [DashboardController::class, 'receptionist'])->name('receptionist.dashboard');
        Route::get('/receptionist/notifications', [NotificationController::class, 'index'])->name('receptionist.notifications');
    });

    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patient'])->name('patient.dashboard');
        Route::get('/patient/notifications', [NotificationController::class, 'index'])->name('patient.notifications');
    });

    Route::middleware(['role:pharmacist'])->group(function () {
        Route::get('/pharmacist/dashboard', [DashboardController::class, 'pharmacist'])->name('pharmacist.dashboard');
        Route::get('/pharmacist/notifications', [NotificationController::class, 'index'])->name('pharmacist.notifications');
    });

    Route::middleware(['role:lab_technician'])->group(function () {
        Route::get('/lab/dashboard', [DashboardController::class, 'lab'])->name('lab.dashboard');
        Route::get('/lab/notifications', [NotificationController::class, 'index'])->name('lab.notifications');
    });

    // Profile Routes (Web)
    // Messages/Chat
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', [MessagesController::class, 'index'])->name('messages');
        Route::get('create', [MessagesController::class, 'create'])->name('messages.create');
        Route::post('/', [MessagesController::class, 'store'])->name('messages.store');
        Route::get('{id}', [MessagesController::class, 'show'])->name('messages.show');
        Route::put('{id}', [MessagesController::class, 'update'])->name('messages.update');
    });
    route::get('/voice-call/{message}', function ($message) {
        \App\Events\SignalingEvent::dispatch($message);
        return view('voice-call', compact('message'));
    })->name('voice.call');
});


Route::get('auth/google', [GoogleCalendarController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
Route::post('events', [GoogleCalendarController::class, 'storeEvent'])->name('events.store');

Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

require __DIR__ . '/auth.php';
