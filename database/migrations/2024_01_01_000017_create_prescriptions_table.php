<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('prescription_number', 20)->unique();
            $table->foreignId('consultation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('patient_profile_id')->constrained()->onDelete('restrict');
            $table->foreignId('doctor_profile_id')->constrained()->onDelete('restrict');
            $table->date('issued_date')->index();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'filled', 'expired', 'cancelled'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
