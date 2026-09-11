<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('appointment_number', 20)->unique()->nullable();
            $table->foreignId('patient_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('doctor_profile_id')->constrained()->onDelete('restrict');
            $table->foreignId('location_id')->constrained()->onDelete('restrict');
            $table->foreignId('appointment_type_id')->constrained()->onDelete('restrict');
            $table->date('scheduled_date')->index();
            $table->time('scheduled_time');
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->enum('status', [
                'scheduled',
                'confirmed',
                'in_progress',
                'completed',
                'cancelled',
                'no_show'
            ])->default('scheduled')->index();
            $table->boolean('is_walk_in')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->foreignId('recurring_parent_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheduled_date', 'scheduled_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
