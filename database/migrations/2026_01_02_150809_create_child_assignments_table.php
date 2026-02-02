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
        Schema::create('child_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('caregiver_id')->constrained('users')->onDelete('cascade'); // Assuming caregivers are in users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_assignments');
    }
};
