<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }

    /**
     * Tampilkan dashboard user dengan ringkasan stat, tiket aktif/mendatang,
     * pesanan & pembayaran, rekomendasi event, notifikasi tugas, dan profil ringkas.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Ambil pesanan terbaru user beserta relasi penting
        $orders = Order::with([
                'items.ticket.event:id,title,slug,location,start_date,end_date,is_online',
                'payments' // untuk cek waktu upload bukti (created_at) dan status
            ])
            ->forUser($user->id)
            ->latest('id')
            ->take(15)
            ->get();

        // Stat ringkas
        $pendingCount = $orders->where('status', 'pending')->count();
        $paidCount = $orders->where('status', 'paid')->count();

        // Event aktif/mendatang yang dimiliki user dari order items (paid/pending)
        $upcomingTickets = OrderItem::with(['ticket.event:id,title,slug,location,start_date,end_date,is_online'])
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('status', ['paid', 'pending']);
            })
            ->whereHas('ticket.event', function ($q) {
                $q->where('end_date', '>', now());
            })
            ->latest('id')
            ->take(10)
            ->get();

        // Unique event list dari tickets untuk ditampilkan sebagai kartu event mendatang
        $upcomingEvents = $upcomingTickets->pluck('ticket.event')->filter()->unique('id')->values();

        // Notifikasi tugas sederhana (mis. pesanan pending = perlu lanjut pembayaran / unggah bukti)
        $tasks = [];
        foreach ($orders->where('status', 'pending') as $pendingOrder) {
            $hasSubmittedPayment = $pendingOrder->payments->contains(fn($p) => $p->status === 'submitted');
            $tasks[] = [
                'invoice' => $pendingOrder->invoice_number,
                'order_id' => $pendingOrder->id,
                'type' => $hasSubmittedPayment ? 'Menunggu verifikasi' : 'Perlu pembayaran/unggah bukti',
                'cta_route' => $hasSubmittedPayment ? route('payments.submitted', ['order' => $pendingOrder->id]) : route('payments.create', ['order' => $pendingOrder->id]),
                'cta_label' => $hasSubmittedPayment ? 'Lihat status pembayaran' : 'Lanjutkan pembayaran',
            ];
        }

        $tasksCount = count($tasks);

        // Rekomendasi event (simple): published + active terbaru
        $recommended = Event::published()
            ->active()
            ->latest('id')
            ->take(4)
            ->get(['id','title','slug','location','start_date','end_date','is_online']);

        $stats = [
            'pending' => $pendingCount,
            'paid' => $paidCount,
            'active_tickets' => $upcomingEvents->count(),
            'tasks' => $tasksCount,
        ];

        // Chart dataset dihapus (dashboard user tanpa chart) untuk efisiensi.

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'orders' => $orders,
            'upcomingEvents' => $upcomingEvents,
            'recommended' => $recommended,
            'tasks' => $tasks,
        ]);
    }
}