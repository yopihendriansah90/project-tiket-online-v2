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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi ke order
            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade');

            // Channel pembayaran manual: bank & e-wallet
            $table->enum('channel', [
                'bri',
                'mandiri',
                'bca',
                'dana',
                'ovo',
                'gopay',
                'shopeepay',
            ]);

            // Nominal yang ditransfer (harus sesuai total order)
            $table->decimal('amount', 10, 2);

            // Path bukti transfer (disimpan di storage)
            $table->string('proof_path')->nullable();

            // Status verifikasi bukti transfer
            // submitted: user sudah unggah bukti
            // verified: admin memverifikasi bukti (order akan ditandai paid)
            // rejected: bukti ditolak (kurang jelas/tidak valid)
            $table->enum('status', ['submitted', 'verified', 'rejected'])->default('submitted');

            // Catatan tambahan (opsional)
            $table->text('notes')->nullable();

            // Informasi verifikasi oleh admin (opsional)
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};