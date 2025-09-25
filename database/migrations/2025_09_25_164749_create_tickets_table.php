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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00); // Harga tiket
            $table->unsignedInteger('quantity')->nullable()->comment('Kuota tiket');
            $table->dateTime('available_from')->comment('Mulai dijual');
            $table->dateTime('available_to')->comment('Batas akhir penjualan');
            $table->timestamps();
           $table->softDeletes(); // Tambahkan Soft Deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
