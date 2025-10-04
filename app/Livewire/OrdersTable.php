<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    /**
     * Query string sync for filters and pagination.
     */
    protected $queryString = [
        'status' => ['except' => 'all'],
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
    public string $status = 'all';
    public string $search = '';
    public int $perPage = 10;

    /**
     * Reset page when filters change.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Computed property: orders collection with pagination.
     * Access via $this->orders in render().
     */
    public function getOrdersProperty()
    {
        $query = Order::with([
                'items.ticket.event:id,title,slug,location,start_date,end_date,is_online',
                'payments',
            ])
            ->forUser(Auth::id())
            ->latest('id');

        // Filter status
        if ($this->status === 'paid') {
            $query->paid();
        } elseif ($this->status === 'pending') {
            $query->pending();
        }

        // Search by invoice number or event title
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('items.ticket.event', function ($qe) use ($search) {
                      $qe->where('title', 'like', "%{$search}%");
                  });
            });
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Render Livewire view with orders data.
     */
    public function render()
    {
        return view('livewire.orders-table', [
            'orders' => $this->orders,
            'status' => $this->status,
            'search' => $this->search,
            'perPage' => $this->perPage,
        ]);
    }
}