<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class ETicketController extends Controller
{
    /**
     * Tampilkan halaman E-ticket yang dapat dicetak/diunduh untuk OrderItem milik user (status paid).
     */
    public function show(Request $request, OrderItem $orderItem)
    {
        $user = $request->user();

        // Muat relasi penting
        $orderItem->load([
            'order:id,user_id,status,invoice_number',
            'ticket.event:id,title,slug,location,start_date,end_date,is_online',
            'attendees.seat',
        ]);

        // Otorisasi kepemilikan
        if (!$orderItem->order || $orderItem->order->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses e-ticket ini.');
        }

        // Wajib sudah terbayar
        if ($orderItem->order->status !== 'paid') {
            return redirect()
                ->route('payments.create', ['order' => $orderItem->order->id])
                ->with('error', 'Selesaikan pembayaran untuk mengakses e-ticket.');
        }

        // Data QR: pakai token attendee jika ada, jika tidak fallback ke komposit sederhana
        $attendee = $orderItem->attendees->first();
        $qrData = $attendee?->unique_token ?? ('ORDERITEM:' . $orderItem->id . '|USER:' . $user->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . urlencode($qrData);

        return view('tickets.eticket', [
            'orderItem' => $orderItem,
            'event' => optional($orderItem->ticket)->event,
            'ticket' => $orderItem->ticket,
            'attendees' => $orderItem->attendees,
            'user' => $user,
            'qrUrl' => $qrUrl,
            'qrData' => $qrData,
        ]);
    }
}