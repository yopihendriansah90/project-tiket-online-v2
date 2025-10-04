<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TasksList extends Component
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
     * Computed property: pending orders for the user, eager loaded for payment status check.
     */
    public function getPendingOrdersProperty()
    {
        $query = Order::with(['payments'])
            ->forUser(Auth::id())
            ->pending()
            ->latest('id');

        // Search by invoice number
        if (!empty($this->search)) {
            $query->where('invoice_number', 'like', '%' . $this->search . '%');
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Render Livewire view with tasks derived from pending orders.
     */
    public function render()
    {
        $orders = $this->pendingOrders;

        // Derive tasks array for summary count (optional)
        $tasksCount = 0;
        foreach ($orders as $order) {
            $hasSubmittedPayment = $order->payments->contains(fn($p) => $p->status === 'submitted');
            // Each order corresponds to one actionable task
            $tasksCount += 1;
        }

        return view('livewire.tasks-list', [
            'orders' => $orders,
            'search' => $this->search,
            'perPage' => $this->perPage,
            'tasksCount' => $tasksCount,
        ]);
    }
}