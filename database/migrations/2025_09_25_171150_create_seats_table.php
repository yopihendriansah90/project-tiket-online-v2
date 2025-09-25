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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            // Relasi ke Event dan Ticket
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained()->onDelete('restrict')->comment('Jenis tiket (misal VIP) yang terkait dengan kursi ini.');

            // Detail Lokasi Kursi
            $table->string('area')->comment('Zona tempat duduk (misal: VIP A)');
            $table->string('row');
            $table->unsignedSmallInteger('number');

            // Status Ketersediaan
            $table->boolean('is_available')->default(true);
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('set null')->comment('Kunci ke item order jika sudah dibeli.');
            $table->timestamp('reserved_until')->nullable()->comment('Waktu reservasi sementara saat checkout.');

            // Index dan Constraint Unik
            $table->unique(['event_id', 'row', 'number'], 'event_seat_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
