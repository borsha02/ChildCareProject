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
        Schema::table('event_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('event_registrations', 'child_id')) {
                $table->foreignId('child_id')->nullable()->after('user_id')->constrained('children')->onDelete('cascade');
            }
            
            // Drop old unique constraint (user_id + event_id)
            // $table->dropUnique(['event_id', 'user_id']);
            
            // Add new unique constraint (event_id + child_id)
            // $table->unique(['event_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
             $table->dropForeign(['child_id']);
             $table->dropUnique(['event_id', 'child_id']);
             $table->dropColumn('child_id');
             
             $table->unique(['event_id', 'user_id']);
        });
    }
};
