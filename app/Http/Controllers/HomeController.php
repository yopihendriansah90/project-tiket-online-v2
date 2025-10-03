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
        // Cache untuk performa homepage
        $cacheKey = 'homepage_events_v2';
        
        [$latestEvents, $popularEvents, $stats] = \Cache::remember($cacheKey, 300, function () {
            // Ambil event terbaru dengan eager loading
            $latestEvents = Event::with(['tickets:id,event_id,name,price', 'media', 'user:id,name'])
                ->published()
                ->active()
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Ambil event populer berdasarkan random untuk demo
            $popularEvents = Event::with(['tickets:id,event_id,name,price', 'media', 'user:id,name'])
                ->published()
                ->active()
                ->inRandomOrder()
                ->take(8)
                ->get();

            // Stats untuk homepage
            $stats = [
                'total_events' => Event::published()->count() + rand(500, 1000),
                'total_customers' => rand(10000, 50000),
                'total_organizers' => Event::distinct('user_id')->count() + rand(100, 500),
                'customer_satisfaction' => 98
            ];

            return [$latestEvents, $popularEvents, $stats];
        });

        // Add mock popularity data untuk demo
        $latestEvents->each(function ($event) {
            $event->attendees_count = rand(50, 500);
            $event->rating = rand(40, 50) / 10; // 4.0 - 5.0
            $event->reviews_count = rand(10, 100);
        });

        $popularEvents->each(function ($event) {
            $event->attendees_count = rand(100, 1000);
            $event->rating = rand(42, 50) / 10;
            $event->reviews_count = rand(50, 200);
        });

        return view('home', compact('latestEvents', 'popularEvents', 'stats'));
    }
}
