<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['doctor_profile_id', 'patient_profile_id']);
            $table->index(['start', 'end']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['patient_id', 'status']);
            $table->index(['doctor_profile_id', 'scheduled_date']);
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->index(['patient_profile_id', 'created_at']);
        });

        Schema::table('vital_signs', function (Blueprint $table) {
            $table->index('patient_profile_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['patient_id', 'status']);
        });

        Schema::table('medical_histories', function (Blueprint $table) {
            $table->index(['patient_profile_id', 'is_active']);
        });

        Schema::table('doctor_time_offs', function (Blueprint $table) {
            $table->index(['doctor_profile_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['doctor_profile_id', 'patient_profile_id']);
            $table->dropIndex(['start', 'end']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['patient_id', 'status']);
            $table->dropIndex(['doctor_profile_id', 'scheduled_date']);
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->dropIndex(['patient_profile_id', 'created_at']);
        });

        Schema::table('vital_signs', function (Blueprint $table) {
            $table->dropIndex(['patient_profile_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['patient_id', 'status']);
        });

        Schema::table('medical_histories', function (Blueprint $table) {
            $table->dropIndex(['patient_profile_id', 'is_active']);
        });

        Schema::table('doctor_time_offs', function (Blueprint $table) {
            $table->dropIndex(['doctor_profile_id', 'start_date', 'end_date']);
        });
    }
};
