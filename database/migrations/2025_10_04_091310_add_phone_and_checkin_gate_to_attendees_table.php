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
        // Add phone column (required at app level) and index
        if (!Schema::hasColumn('attendees', 'phone')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->string('phone', 20)->after('email')->comment('Nomor HP peserta');
                $table->index('phone');
            });
        }

        // Add checkin_gate column (nullable)
        if (!Schema::hasColumn('attendees', 'checkin_gate')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->string('checkin_gate', 50)->nullable()->after('checked_in_at')->comment('Gate atau pos check-in');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop phone index and column if exists
        if (Schema::hasColumn('attendees', 'phone')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->dropIndex(['phone']);
                $table->dropColumn('phone');
            });
        }

        // Drop checkin_gate column if exists
        if (Schema::hasColumn('attendees', 'checkin_gate')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->dropColumn('checkin_gate');
            });
        }
    }
};