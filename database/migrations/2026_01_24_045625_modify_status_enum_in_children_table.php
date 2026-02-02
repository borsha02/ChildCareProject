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
        Schema::table('children', function (Blueprint $table) {
            DB::statement("ALTER TABLE children MODIFY COLUMN status ENUM('pending', 'active', 'inactive', 'rejected', 'activation_requested') NOT NULL DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
             DB::statement("ALTER TABLE children MODIFY COLUMN status ENUM('pending', 'active', 'inactive', 'rejected') NOT NULL DEFAULT 'pending'");
        });
    }
};
