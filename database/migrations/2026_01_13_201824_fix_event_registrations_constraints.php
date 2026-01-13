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
        Schema::disableForeignKeyConstraints();
        
        Schema::table('event_registrations', function (Blueprint $table) {
            // Drop by array notation (Laravel infers index name)
            $table->dropUnique(['event_id', 'user_id']);
            
            // Add new unique constraint
            $table->unique(['event_id', 'child_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'child_id']);
            $table->unique(['event_id', 'user_id']);
        });
    }
};
