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
        Schema::table('tickets', function (Blueprint $table) {
             // Kolom baru untuk identifikasi zona/area tiket
            $table->string('seat_area')
                ->nullable()
                ->after('name')
                ->comment('Area tempat duduk (misal: Lantai 1, VIP) jika menggunakan kursi bernomor.');

            // Kolom baru untuk mengaktifkan pemilihan kursi per jenis tiket
            $table->boolean('is_seating_enabled')
                ->default(false)
                ->after('seat_area');

            // Opsional: Jika menggunakan kursi, kuota tidak relevan
            // Anda bisa menghapus kolom 'quantity' atau membiarkannya (nullable)
            $table->unsignedInteger('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['seat_area', 'is_seating_enabled']);
            // Revert quantity back to non-nullable (jika sebelumnya tidak nullable)
            // $table->unsignedInteger('quantity')->change();
        });
    }
};
