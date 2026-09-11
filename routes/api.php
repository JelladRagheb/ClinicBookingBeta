<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\GoogleCalendarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix("v1")->group(function () {
    // Authentication
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    // Protected routes
    Route::middleware("auth:api")->group(function () {
        // Auth
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/me", [AuthController::class, "me"]);
        Route::post("/refresh", [AuthController::class, "refresh"]);

        // Profile
        Route::get("/profile", [ProfileController::class, "getProfile"]);
        Route::put("/profile", [ProfileController::class, "update"]);
        Route::put("/profile/patient", [
            ProfileController::class,
            "updatePatientProfile",
        ]);
        Route::put("/profile/password", [
            ProfileController::class,
            "changePassword",
        ]);
        Route::delete("/profile/avatar", [
            ProfileController::class,
            "deleteAvatar",
        ]);

        // Appointments
        Route::get("/appointments", [AppointmentController::class, "index"]);
        Route::post("/appointments", [
            AppointmentController::class,
            "appointments.store",
            function () {
                return "hello";
            },
        ]);
        Route::get("/appointments/{id}", [
            AppointmentController::class,
            "show",
        ]);
        Route::put("/appointments/{id}", [
            AppointmentController::class,
            "update",
        ]);
        Route::delete("/appointments/{id}", [
            AppointmentController::class,
            "destroy",
        ]);
        Route::get("/appointments/{id}/cancel", [
            AppointmentController::class,
            "cancel",
        ]);
        Route::get("/appointments/{id}/confirm", [
            AppointmentController::class,
            "confirm",
        ]);
        Route::get("/appointments/{id}/reschedule", [
            AppointmentController::class,
            "reschedule",
        ]);
        Route::get("/appointments/{id}/rate", [
            AppointmentController::class,
            "rate",
        ]);
        Route::get("/appointments/{id}/rate", [
            AppointmentController::class,
            "rate",
        ]);
    });

    //Google Calendar
});
// Route::get('auth/google', [GoogleCalendarController::class, 'redirectToGoogle']);
// Route::get('auth/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
// Route::post('events', [GoogleCalendarController::class, 'storeEvent'])->name('events.store');
