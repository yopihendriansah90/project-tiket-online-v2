<?php

namespace Tests\Feature\Commands;

use App\Models\Seat;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CleanupExpiredReservationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_cleans_up_expired_reservations()
    {
        $event = Event::factory()->create();
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);

        // Create expired reservation
        $expiredSeat = Seat::factory()->create([
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'is_available' => false,
            'reserved_until' => now()->subMinutes(10),
            'order_item_id' => null
        ]);

        // Create valid reservation
        $validSeat = Seat::factory()->create([
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'is_available' => false,
            'reserved_until' => now()->addMinutes(10),
            'order_item_id' => null
        ]);

        // Create order and order item for sold seat
        $order = \App\Models\Order::factory()->create(['status' => 'paid']);
        $orderItem = \App\Models\OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_id' => $ticket->id
        ]);
        
        // Create already sold seat (should not be affected)
        $soldSeat = Seat::factory()->create([
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'is_available' => false,
            'reserved_until' => now()->subMinutes(10),
            'order_item_id' => $orderItem->id
        ]);

        $this->artisan('reservations:cleanup')
            ->assertExitCode(0);

        // Refresh models
        $expiredSeat->refresh();
        $validSeat->refresh();
        $soldSeat->refresh();

        // Expired seat should be cleaned up
        $this->assertTrue($expiredSeat->is_available);
        $this->assertNull($expiredSeat->reserved_until);

        // Valid reservation should remain unchanged
        $this->assertFalse($validSeat->is_available);
        $this->assertNotNull($validSeat->reserved_until);

        // Sold seat should remain unchanged
        $this->assertFalse($soldSeat->is_available);
        $this->assertEquals($orderItem->id, $soldSeat->order_item_id);
    }

    #[Test]
    public function it_reports_correct_cleanup_count()
    {
        $event = Event::factory()->create();
        $ticket = Ticket::factory()->create(['event_id' => $event->id]);

        // Create 3 expired reservations
        Seat::factory()->count(3)->create([
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'is_available' => false,
            'reserved_until' => now()->subMinutes(10),
            'order_item_id' => null
        ]);

        $this->artisan('reservations:cleanup')
            ->expectsOutput('Cleaned up 3 expired seat reservations.')
            ->assertExitCode(0);
    }
}