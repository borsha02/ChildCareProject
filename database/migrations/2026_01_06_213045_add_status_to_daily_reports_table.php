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
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->enum('status', ['draft', 'completed'])->default('draft')->after('medications_included');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            //
        });
    }
};
