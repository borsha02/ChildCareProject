<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sanitize existing data to prevent "Data truncated" error
        // Update any position that is NOT in the new allowed list to a default valid value
        DB::table('job_applications')
            ->whereNotIn('position', ['Senior teacher', 'Assistant teacher', 'Junior teacher'])
            ->update(['position' => 'Assistant teacher']); // Defaulting to Assistant teacher

        // Using raw SQL to avoid doctrine/dbal dependency for ENUM modification
        DB::statement("ALTER TABLE job_applications MODIFY position ENUM('Senior teacher', 'Assistant teacher', 'Junior teacher') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to string/varchar
        DB::statement("ALTER TABLE job_applications MODIFY position VARCHAR(255) NOT NULL");
    }
};
