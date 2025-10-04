<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman instruksi pembayaran manual + form upload bukti.
     */
    public function create(Order $order): View
    {
        // Pastikan user pemilik order dan status masih pending
        abort_if($order->user_id !== Auth::id(), 404);
        abort_if($order->status !== 'pending', 404);

        $channels = [
            'bri' => 'BRI',
            'mandiri' => 'Mandiri',
            'bca' => 'BCA',
            'dana' => 'DANA',
            'ovo' => 'OVO',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
        ];

        // Ambil pembayaran terakhir (jika ada) untuk ditampilkan statusnya
        $latestPayment = $order->payments()->latest('id')->first();

        return view('payments.create', [
            'order' => $order,
            'channels' => $channels,
            'latestPayment' => $latestPayment,
        ]);
    }

    /**
     * Simpan bukti transfer pembayaran manual.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        // Validasi user dan status order
        abort_if($order->user_id !== Auth::id(), 404);
        abort_if($order->status !== 'pending', 404);

        $request->validate([
            'channel' => ['required', 'in:' . implode(',', Payment::CHANNELS)],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'], // 5MB
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'channel.required' => 'Silakan pilih metode transfer.',
            'channel.in' => 'Metode transfer tidak valid.',
            'proof.required' => 'Silakan unggah bukti transfer.',
            'proof.mimes' => 'Bukti harus berupa gambar (jpg,jpeg,png,webp) atau PDF.',
            'proof.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Simpan file bukti ke disk "public"
        $path = $request->file('proof')->store('payments/proofs', 'public');

        // Buat record Payment
        $payment = Payment::create([
            'order_id' => $order->id,
            'channel' => $request->string('channel'),
            'amount' => $order->total_price, // selalu mengikuti total order
            'proof_path' => $path,
            'status' => 'submitted',
            'notes' => $request->string('notes'),
            'verified_by' => null,
            'verified_at' => null,
        ]);

        // Tandai metode pembayaran order sebagai manual transfer (jika belum)
        if ($order->payment_method !== 'manual_transfer') {
            $order->update(['payment_method' => 'manual_transfer']);
        }

        return redirect()
            ->route('payments.create', ['order' => $order->id])
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu verifikasi admin.');
    }
}