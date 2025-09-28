<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Collection;

class TicketSelector extends Component
{
    public Event $event;
    public Collection $tickets;
    public array $quantities = [];
    public float $totalPrice = 0.0;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->tickets = $this->event->tickets()->where('available_from', '<=', now())->where('available_to', '>=', now())->get();
        $this->quantities = $this->tickets->mapWithKeys(fn ($ticket) => [$ticket->id => 0])->toArray();
    }

    public function updatedQuantities()
    {
        $this->calculateTotalPrice();
    }

    public function increment($ticketId)
    {
        $ticket = $this->tickets->find($ticketId);
        if ($this->quantities[$ticketId] < $ticket->quantity) {
            $this->quantities[$ticketId]++;
            $this->calculateTotalPrice();
        }
    }

    public function decrement($ticketId)
    {
        if ($this->quantities[$ticketId] > 0) {
            $this->quantities[$ticketId]--;
            $this->calculateTotalPrice();
        }
    }

    private function calculateTotalPrice()
    {
        $this->totalPrice = $this->tickets->reduce(function ($carry, $ticket) {
            return $carry + ($this->quantities[$ticket->id] * $ticket->price);
        }, 0);
    }

    public function render()
    {
        return view('livewire.ticket-selector');
    }
}