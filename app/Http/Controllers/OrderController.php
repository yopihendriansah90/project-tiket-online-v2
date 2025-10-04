<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Simpan order tiket (tanpa pembayaran).
     * - Validasi input
     * - Cek ketersediaan tiket & stok
     * - Buat Order dan OrderItem dengan status "pending"
     * - Redirect kembali ke halaman event dengan pesan sukses
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'ticket_id' => ['required', 'exists:tickets,id'],
            'quantity'  => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'event_id.required' => 'Event tidak valid.',
            'ticket_id.required' => 'Silakan pilih jenis tiket.',
            'quantity.required' => 'Silakan pilih jumlah tiket.',
            'quantity.min' => 'Jumlah minimal 1.',
            'quantity.max' => 'Jumlah maksimal 10 per order.',
        ]);

        // Ambil tiket dan pastikan milik event yang dimaksud
        $ticket = Ticket::with('event')
            ->where('id', $validated['ticket_id'])
            ->where('event_id', $validated['event_id'])
            ->firstOrFail();

        // Pastikan window ketersediaan tiket
        if (!($ticket->available_from <= now() && $ticket->available_to >= now())) {
            return back()
                ->withErrors(['ticket_id' => 'Tiket tidak tersedia saat ini.'])
                ->withInput();
        }

        // Pastikan stok mencukupi
        $requestedQty = (int) $validated['quantity'];
        if ($requestedQty > $ticket->available_stock) {
            return back()
                ->withErrors(['quantity' => 'Jumlah melebihi stok tersedia.'])
                ->withInput();
        }

        $user = $request->user();

        // Buat order + item dalam transaksi
        return DB::transaction(function () use ($ticket, $requestedQty, $user) {
            $subtotal = $ticket->price * $requestedQty;

            $order = Order::create([
                'user_id'        => $user->id,
                'invoice_number' => Order::generateInvoiceNumber(),
                'total_price'    => $subtotal,
                'status'         => 'pending', // belum bayar
                'payment_method' => null,
                'paid_at'        => null,
            ]);

            OrderItem::create([
                'order_id'    => $order->id,
                'ticket_id'   => $ticket->id,
                'ticket_name' => $ticket->name,
                'price'       => $ticket->price,
                'quantity'    => $requestedQty,
                'subtotal'    => $subtotal,
            ]);

            // Catatan: Stok dihitung dari OrderItem (status pending/paid),
            // jadi setelah membuat OrderItem dengan status order pending,
            // stok efektif sudah "terpakai".

            return redirect()
                ->route('payments.create', ['order' => $order->id])
                ->with('success', 'Order berhasil dibuat. Nomor invoice: ' . $order->invoice_number . '. Silakan lanjutkan ke halaman pembayaran untuk upload bukti transfer.');
        });
    }
}