<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Attendee;
use App\Models\Seat;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TicketSelector extends Component
{
    public Event $event;
    public Collection $tickets;
    public array $quantities = [];
    public float $totalPrice = 0.0;
    public array $availableStock = [];
    public string $step = 'select';
    public array $attendeesData = [];

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

    /**
     * Step 1 -> Step 2
     * Menyusun struktur form data peserta berdasarkan quantities yang dipilih.
     */
    public function proceedToAttendees(): void
    {
        // Pastikan ada minimal 1 tiket dipilih
        $selectedCount = array_sum(array_map(fn($q) => (int) $q, $this->quantities));
        if ($selectedCount <= 0) {
            $this->dispatch('error', ['message' => 'Pilih minimal 1 tiket untuk melanjutkan.']);
            return;
        }

        // Susun struktur attendeesData per ticketId
        foreach ($this->quantities as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                unset($this->attendeesData[$ticketId]);
                continue;
            }

            $existing = $this->attendeesData[$ticketId] ?? [];
            $next = [];

            // Pertahankan data yang sudah diisi jika ada
            for ($i = 0; $i < $qty; $i++) {
                $next[$i] = [
                    'name' => $existing[$i]['name'] ?? '',
                    'phone' => $existing[$i]['phone'] ?? '',
                ];
            }

            $this->attendeesData[$ticketId] = $next;
        }

        $this->step = 'attendees';
    }

    /**
     * Kembali ke Step 1 (ubah jumlah tiket).
     */
    public function backToSelect(): void
    {
        $this->step = 'select';
    }

    /**
     * Submit Data Peserta:
     * - Validasi seluruh peserta
     * - Re-check stok
     * - Buat Order + OrderItems + Attendees
     * - (Opsional) Auto-assign kursi jika event menggunakan kursi bernomor
     * - Redirect ke halaman pembayaran
     */
    public function submitAttendees()
    {
        // Kumpulkan ticket yang dipilih beserta qty
        $selected = [];
        foreach ($this->quantities as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                $selected[(int)$ticketId] = $qty;
            }
        }

        if (empty($selected)) {
            $this->dispatch('error', ['message' => 'Tidak ada tiket dipilih.']);
            return;
        }

        // Validasi dinamis untuk data peserta
        $rules = [];
        $messages = [];
        foreach ($selected as $ticketId => $qty) {
            for ($i = 0; $i < $qty; $i++) {
                $rules["attendeesData.$ticketId.$i.name"] = 'required|string|max:255|min:2';
                $rules["attendeesData.$ticketId.$i.phone"] = 'required|string|max:20';
                $messages["attendeesData.$ticketId.$i.name.required"] = "Nama peserta #".($i+1)." untuk tiket $ticketId wajib diisi.";
                $messages["attendeesData.$ticketId.$i.phone.required"] = "Nomor HP peserta #".($i+1)." untuk tiket $ticketId wajib diisi.";
            }
        }
        $this->validate($rules, $messages);

        // Re-check stok real-time dan hitung total
        $ticketsMap = $this->tickets->keyBy('id');
        $computedTotal = 0;

        foreach ($selected as $ticketId => $qty) {
            /** @var \App\Models\Ticket|null $ticket */
            $ticket = $ticketsMap->get($ticketId);
            if (!$ticket) {
                $this->addError("quantities.$ticketId", 'Tiket tidak ditemukan.');
                return;
            }

            // Hitung stok terkini
            $soldTickets = OrderItem::where('ticket_id', $ticketId)
                ->whereHas('order', fn($q) => $q->whereIn('status', ['paid', 'pending']))
                ->sum('quantity');

            $availableStock = max(0, $ticket->quantity - $soldTickets);
            if ($qty > $availableStock) {
                $this->addError("quantities.$ticketId", 'Jumlah melebihi stok tersedia saat ini.');
                return;
            }

            $computedTotal += ($ticket->price * $qty);
        }

        try {
            $orderId = DB::transaction(function () use ($selected, $ticketsMap, $computedTotal) {
                // Buat Order pending
                /** @var \App\Models\Order $order */
                $order = Order::create([
                    'user_id'        => Auth::id(),
                    'invoice_number' => Order::generateInvoiceNumber(),
                    'total_price'    => $computedTotal,
                    'status'         => 'pending',
                    'payment_method' => null,
                    'paid_at'        => null,
                ]);

                // Buat OrderItems per ticketId
                $orderItemsByTicket = [];
                foreach ($selected as $ticketId => $qty) {
                    /** @var \App\Models\Ticket $ticket */
                    $ticket = $ticketsMap->get($ticketId);

                    $orderItemsByTicket[$ticketId] = OrderItem::create([
                        'order_id'    => $order->id,
                        'ticket_id'   => $ticket->id,
                        'ticket_name' => $ticket->name,
                        'price'       => $ticket->price,
                        'quantity'    => $qty,
                        'subtotal'    => $ticket->price * $qty,
                    ]);
                }

                // Buat Attendees per orang, dan (opsional) alokasi kursi
                $useNumberedSeats = (bool) $this->event->has_numbered_seats;

                foreach ($selected as $ticketId => $qty) {
                    /** @var \App\Models\OrderItem $orderItem */
                    $orderItem = $orderItemsByTicket[$ticketId];

                    for ($i = 0; $i < $qty; $i++) {
                        $data = $this->attendeesData[$ticketId][$i] ?? ['name' => '', 'phone' => ''];

                        $seatId = null;
                        if ($useNumberedSeats) {
                            // Ambil kursi available berdasarkan ticket_id
                            $seat = Seat::where('ticket_id', $ticketId)
                                ->available()
                                ->lockForUpdate()
                                ->first();

                            if (!$seat) {
                                throw new \RuntimeException('Kursi tidak mencukupi untuk alokasi otomatis.');
                            }

                            // Tandai kursi sebagai terpakai oleh order item ini
                            $seat->update([
                                'is_available'   => false,
                                'reserved_until' => null,
                                'order_item_id'  => $orderItem->id,
                            ]);

                            $seatId = $seat->id;
                        }

                        Attendee::create([
                            'order_item_id' => $orderItem->id,
                            'user_id'       => Auth::id(),
                            'name'          => $data['name'],
                            // Kolom email opsional; belum wajib diisi fase ini
                            'email'         => null,
                            // Simpan phone jika sudah ada kolom di DB (migrasi akan ditambahkan)
                            // Jika kolom belum ada, Laravel akan mengabaikan field ini saat mass-assignment.
                            'phone'         => $data['phone'] ?? null,
                            'unique_token'  => Attendee::generateUniqueToken(),
                            'seat_id'       => $seatId,
                            'checked_in_at' => null,
                        ]);
                    }
                }

                return $order->id;
            }, 3);

            // Redirect ke halaman pembayaran
            return redirect()->route('payments.create', ['order' => $orderId]);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', ['message' => 'Gagal membuat order: ' . $e->getMessage()]);
            return;
        }
    }

    public function render()
    {
        // Refresh stock before render to show latest data
        $this->refreshAvailableStock();
        
        return view('livewire.ticket-selector');
    }
}