<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TicketSelector extends Component
{
    public Event $event;
    public Collection $tickets;
    public array $quantities = [];
    public float $totalPrice = 0.0;
    public array $availableStock = [];

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->tickets = $this->event->tickets()
            ->where('available_from', '<=', now())
            ->where('available_to', '>=', now())
            ->get();
        $this->quantities = $this->tickets->mapWithKeys(fn ($ticket) => [$ticket->id => 0])->toArray();
        $this->refreshAvailableStock();
    }

    public function updatedQuantities()
    {
        $this->calculateTotalPrice();
    }

    public function increment($ticketId)
    {
        try {
            DB::transaction(function () use ($ticketId) {
                // Lock ticket for update to prevent race condition
                $ticket = Ticket::where('id', $ticketId)->lockForUpdate()->first();
                
                if (!$ticket) {
                    throw new \Exception('Tiket tidak ditemukan');
                }
                
                // Calculate real-time available stock
                $soldTickets = OrderItem::where('ticket_id', $ticketId)
                    ->whereHas('order', fn($q) => $q->whereIn('status', ['paid', 'pending']))
                    ->sum('quantity');
                
                $availableStock = $ticket->quantity - $soldTickets;
                $this->availableStock[$ticketId] = $availableStock;
                
                // Check if we can increment
                if ($this->quantities[$ticketId] < $availableStock) {
                    $this->quantities[$ticketId]++;
                    $this->calculateTotalPrice();
                } else {
                    $this->dispatch('stock-exceeded', [
                        'message' => 'Maaf, stok tiket ' . $ticket->name . ' tidak mencukupi'
                    ]);
                }
            });
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function decrement($ticketId)
    {
        if ($this->quantities[$ticketId] > 0) {
            $this->quantities[$ticketId]--;
            $this->calculateTotalPrice();
        }
    }

    public function refreshAvailableStock()
    {
        // Cache for 5 seconds to prevent excessive DB calls
        $this->availableStock = Cache::remember(
            "ticket_stock_{$this->event->id}",
            5,
            function () {
                $stockData = [];
                foreach ($this->tickets as $ticket) {
                    $soldTickets = OrderItem::where('ticket_id', $ticket->id)
                        ->whereHas('order', fn($q) => $q->whereIn('status', ['paid', 'pending']))
                        ->sum('quantity');
                    
                    $stockData[$ticket->id] = max(0, $ticket->quantity - $soldTickets);
                }
                return $stockData;
            }
        );
    }

    private function calculateTotalPrice()
    {
        $this->totalPrice = $this->tickets->reduce(function ($carry, $ticket) {
            return $carry + ($this->quantities[$ticket->id] * $ticket->price);
        }, 0);
    }

    public function render()
    {
        // Refresh stock before render to show latest data
        $this->refreshAvailableStock();
        
        return view('livewire.ticket-selector');
    }
}