<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Event;
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

        // Simpan bukti menggunakan Spatie Media Library
        $payment = Payment::create([
            'order_id' => $order->id,
            'channel' => $request->input('channel'),
            'amount' => $order->total_price, // selalu mengikuti total order
            'proof_path' => null,
            'status' => 'submitted',
            'notes' => $request->input('notes'),
            'verified_by' => null,
            'verified_at' => null,
        ]);

        // Lampirkan file bukti ke koleksi media "payment_proofs"
        if ($request->hasFile('proof')) {
            $payment
                ->addMediaFromRequest('proof')
                ->usingName('Bukti Transfer ' . ($order->invoice_number ?? $order->id))
                ->toMediaCollection('payment_proofs');
        }

        // Tandai metode pembayaran order sebagai manual transfer (jika belum)
        if ($order->payment_method !== 'manual_transfer') {
            $order->update(['payment_method' => 'manual_transfer']);
        }

        return redirect()
            ->route('payments.submitted', ['order' => $order->id])
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Halaman status "bukti terkirim" + rekomendasi event lain.
     */
    public function submitted(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 404);

        $latestPayment = $order->payments()->latest('id')->first();

        // Ambil rekomendasi event lain (simple: 4 event terbaru yang aktif & published)
        $recommended = Event::published()
            ->active()
            ->latest('id')
            ->take(4)
            ->get();

        return view('payments.submitted', [
            'order' => $order,
            'latestPayment' => $latestPayment,
            'recommended' => $recommended,
        ]);
    }

    /**
     * Verifikasi cepat dari modal preview pada admin.
     */
    public function adminVerify(\App\Models\Payment $payment): \Illuminate\Http\RedirectResponse
    {
        // Catatan: Tambahkan middleware/authorization sesuai kebutuhan (mis. admin).
        // Di sini diasumsikan route sudah dilindungi auth dan hanya admin yang punya akses ke panel ini.

        if ($payment->status !== 'verified') {
            $payment->status = 'verified';
            $payment->verified_by = \Illuminate\Support\Facades\Auth::id();
            $payment->verified_at = now();
            $payment->save();

            // Update status order terkait
            $order = $payment->order;
            if ($order) {
                $order->status = 'paid';
                $order->paid_at = now();
                if ($order->payment_method !== 'manual_transfer') {
                    $order->payment_method = 'manual_transfer';
                }
                $order->save();
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}