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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('Bisa null jika tiket dipindahkan/dihadiahkan');
            $table->string('name')->comment('Nama peserta');
            $table->string('email')->nullable();
            $table->string('unique_token')->unique()->comment('Token untuk QR Code');
            $table->timestamp('checked_in_at')->nullable()->comment('Waktu check-in/verifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
