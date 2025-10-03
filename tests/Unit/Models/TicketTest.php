<?php

namespace Tests\Unit\Models;

use App\Models\Ticket;
use App\Models\Event;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_calculates_available_stock_correctly()
    {
        $event = Event::factory()->create();
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'quantity' => 100
        ]);
        
        // Create paid orders
        $order1 = Order::factory()->create(['status' => 'paid']);
        OrderItem::factory()->create([
            'ticket_id' => $ticket->id,
            'order_id' => $order1->id,
            'quantity' => 10
        ]);

        // Create pending orders
        $order2 = Order::factory()->create(['status' => 'pending']);
        OrderItem::factory()->create([
            'ticket_id' => $ticket->id,
            'order_id' => $order2->id,
            'quantity' => 5
        ]);

        // Create failed order (should not affect stock)
        $order3 = Order::factory()->create(['status' => 'failed']);
        OrderItem::factory()->create([
            'ticket_id' => $ticket->id,
            'order_id' => $order3->id,
            'quantity' => 20
        ]);

        $this->assertEquals(85, $ticket->available_stock); // 100 - 10 - 5 = 85
    }

    #[Test]
    public function it_formats_price_correctly()
    {
        $event = Event::factory()->create();
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'price' => 150000
        ]);
        
        $this->assertEquals('Rp 150.000', $ticket->formatted_price);
    }

    #[Test]
    public function available_scope_works_correctly()
    {
        $event = Event::factory()->create();
        
        $availableTicket = Ticket::factory()->create([
            'event_id' => $event->id,
            'available_from' => now()->subHour(),
            'available_to' => now()->addHour()
        ]);
        
        $notYetAvailableTicket = Ticket::factory()->create([
            'event_id' => $event->id,
            'available_from' => now()->addHour(),
            'available_to' => now()->addHours(2)
        ]);
        
        $expiredTicket = Ticket::factory()->create([
            'event_id' => $event->id,
            'available_from' => now()->subHours(2),
            'available_to' => now()->subHour()
        ]);

        $availableTickets = Ticket::available()->get();
        
        $this->assertCount(1, $availableTickets);
        $this->assertEquals($availableTicket->id, $availableTickets->first()->id);
    }
}