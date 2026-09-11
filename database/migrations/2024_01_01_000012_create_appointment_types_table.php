<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_types', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // {fr, ar, en}
            $table->string('slug', 100)->unique();
            $table->json('description')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->decimal('default_fee', 10, 2)->default(0);
            $table->string('color', 7)->default('#3B82F6');
            $table->boolean('requires_preparation')->default(false);
            $table->json('preparation_instructions')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_types');
    }
};
