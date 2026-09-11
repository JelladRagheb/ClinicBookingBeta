<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('payment_number', 20)->unique();
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('patient_id')->constrained('users')->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('TND');
            $table->enum('payment_method', ['cash', 'card', 'konnect', 'insurance', 'other'])->default('cash')->index();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending')->index();
            $table->string('konnect_payment_id')->nullable()->index();
            $table->string('konnect_payment_ref')->nullable();
            $table->timestamp('paid_at')->nullable()->index();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->text('refund_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
