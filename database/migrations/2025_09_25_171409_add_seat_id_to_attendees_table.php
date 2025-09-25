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
        Schema::table('attendees', function (Blueprint $table) {
            $table->foreignId('seat_id')
                ->nullable() // Nullable jika ini adalah tiket non-kursi
                ->constrained()
                ->onDelete('set null')
                ->after('order_item_id')
                ->comment('Kursi spesifik yang didapatkan peserta.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropConstrainedForeignId('seat_id');
            $table->dropColumn('seat_id');
        });
    }
};
