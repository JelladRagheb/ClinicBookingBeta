<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_profile_id')->nullable()->after('id');
            $table->unsignedBigInteger('patient_profile_id')->nullable()->after('doctor_profile_id');

            $table->foreign('doctor_profile_id')->references('id')->on('doctor_profiles')->onDelete('cascade');
            $table->foreign('patient_profile_id')->references('id')->on('patient_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['doctor_profile_id']);
            $table->dropForeign(['patient_profile_id']);
            $table->dropColumn(['doctor_profile_id', 'patient_profile_id']);
        });
    }
};
