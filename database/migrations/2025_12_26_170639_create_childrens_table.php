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
        Schema::create('children', function (Blueprint $table) {
            $table->id();

            // parent relation
            $table->foreignId('parent_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            

            $table->string('first_name');    
            $table->string('last_name');   
            $table->date('dob'); 
            $table->enum('gender', ['male', 'female', 'other']);    
            $table->string('blood_group')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_notes')->nullable();
            $table->string('emergency_contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
