<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Ambil 4 event terbaru yang sudah di-publish
        $latestEvents = Event::where('status', \App\Enums\EventStatus::PUBLISHED)
                             ->orderBy('created_at', 'desc')
                             ->take(4)
                             ->get();

        // Ambil 4 event terpopuler (untuk saat ini kita gunakan event terbaru juga sebagai placeholder)
        // Logika popularitas bisa dikembangkan di sini, misal berdasarkan jumlah tiket terjual.
        $popularEvents = Event::where('status', \App\Enums\EventStatus::PUBLISHED)
                              ->inRandomOrder()
                              ->take(4)
                              ->get();

        return view('home', [
            'latestEvents' => $latestEvents,
            'popularEvents' => $popularEvents,
        ]);
    }
}
