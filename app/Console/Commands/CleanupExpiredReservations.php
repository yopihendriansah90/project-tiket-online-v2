<?php

namespace App\Console\Commands;

use App\Models\Seat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reservations:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Cleanup expired seat reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired seat reservations...');

        $expiredCount = Seat::where('reserved_until', '<', now())
            ->whereNotNull('reserved_until')
            ->whereNull('order_item_id')
            ->count();

        if ($expiredCount > 0) {
            Seat::where('reserved_until', '<', now())
                ->whereNotNull('reserved_until')
                ->whereNull('order_item_id')
                ->update([
                    'reserved_until' => null,
                    'is_available' => true
                ]);

            $this->info("Cleaned up {$expiredCount} expired seat reservations.");
            Log::info("Cleaned up {$expiredCount} expired seat reservations");
        } else {
            $this->info('No expired reservations found.');
        }

        return 0;
    }
}