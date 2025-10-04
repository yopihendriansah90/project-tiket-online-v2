<?php

namespace App\Livewire;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TicketsList extends Component
{
    use WithPagination;

    /**
     * Query string sync for filters and pagination.
     */
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    /**
     * Use Tailwind pagination views.
     */
    protected string $paginationTheme = 'tailwind';

    /**
     * Filters.
     */
    public string $search = '';
    public int $perPage = 8;

    /**
     * Reset page when filters change.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Computed property: upcoming ticket order items for the user.
     */
    public function getItemsProperty()
    {
        $query = OrderItem::with([
                'ticket.event:id,title,slug,location,start_date,end_date,is_online',
                'order:id,user_id,status',
                'attendees.seat',
            ])
            ->whereHas('order', function ($q) {
                $q->where('user_id', Auth::id())
                  ->whereIn('status', ['paid', 'pending']);
            })
            ->whereHas('ticket.event', function ($q) {
                $q->where('end_date', '>', now());
            })
            ->latest('id');

        // Search by event title
        if (!empty($this->search)) {
            $search = $this->search;
            $query->whereHas('ticket.event', function ($qe) use ($search) {
                $qe->where('title', 'like', "%{$search}%");
            });
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Render Livewire view with tickets data.
     */
    public function render()
    {
        return view('livewire.tickets-list', [
            'items' => $this->items,
            'search' => $this->search,
            'perPage' => $this->perPage,
        ]);
    }
}