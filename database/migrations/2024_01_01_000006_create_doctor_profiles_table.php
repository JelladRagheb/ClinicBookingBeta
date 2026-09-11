<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('license_number', 100)->unique();
            $table->json('bio')->nullable(); // {fr, ar, en}
            $table->json('education')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->unsignedTinyInteger('cancellation_hours')->default(24);
            $table->unsignedTinyInteger('reschedule_hours')->default(12);
            $table->boolean('accepts_walk_ins')->default(true);
            $table->boolean('is_available')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
