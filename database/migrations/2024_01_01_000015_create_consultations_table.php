<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('patient_profile_id')->constrained()->onDelete('restrict');
            $table->foreignId('doctor_profile_id')->constrained()->onDelete('restrict');
            $table->text('chief_complaint')->nullable();
            $table->json('symptoms')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->unsignedSmallInteger('follow_up_days')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
