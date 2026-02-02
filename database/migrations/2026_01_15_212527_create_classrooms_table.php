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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Toddler A"
            $table->string('class'); // e.g., "Ages 1-2"
            $table->integer('capacity'); // e.g., 10
            $table->string('teacher_name')->nullable(); // e.g., "Lisa Johnson"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
