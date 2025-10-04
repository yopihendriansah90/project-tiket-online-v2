<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventDisplayController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        // 1. Ambil input dari user dengan sanitasi
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest');

        // Validasi input search untuk keamanan
        if ($search && strlen($search) > 100) {
            $search = substr($search, 0, 100);
        }

        // 2. Cache key untuk performance
        $cacheKey = "events_list_" . md5($search . $sort . $request->get('page', 1));

        // 3. Cache untuk 5 menit
        $events = cache()->remember($cacheKey, 300, function () use ($search, $sort, $request) {
            
            // 4. Query dengan eager loading untuk menghindari N+1
            $query = Event::with(['tickets:id,event_id,name,price'])
                ->published()
                ->active();

            // 5. Filter pencarian dengan sanitasi
            $query->when($search, function ($q, $search) {
                $searchTerm = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';
                return $q->where(function($subQuery) use ($searchTerm) {
                    $subQuery->where('title', 'like', $searchTerm)
                             ->orWhere('location', 'like', $searchTerm)
                             ->orWhere('description', 'like', $searchTerm);
                });
            });

            // 6. Optimized sorting
            switch ($sort) {
                case 'soonest':
                    $query->orderBy('start_date', 'asc');
                    break;
                case 'cheapest':
                    $query->leftJoin('tickets', 'events.id', '=', 'tickets.event_id')
                          ->selectRaw('events.*, MIN(tickets.price) as min_price')
                          ->groupBy('events.id')
                          ->orderBy('min_price', 'asc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            // 7. Paginate dengan appends untuk maintain query params
            return $query->paginate(12)->appends($request->query());
        });

        return view('events.index', compact('events', 'search', 'sort'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Pastikan hanya event yang sudah di-publish yang bisa diakses
        if ($event->status !== \App\Enums\EventStatus::PUBLISHED) {
            abort(404);
        }

        // Cache untuk single event
        $eventData = cache()->remember(
            "event_detail_{$event->id}",
            600, // Cache 10 menit
            function () use ($event) {
                // Eager load semua relasi yang dibutuhkan untuk menghindari N+1
                return $event->load([
                    'tickets' => function($query) {
                        $query->available()->orderBy('price', 'asc');
                    },
                    'seats' => function($query) {
                        $query->available()->orderBy('area')->orderBy('row')->orderBy('number');
                    },
                    'user:id,name',
                    'media'
                ]);
            }
        );

        // Calculate available stock untuk setiap ticket
        $ticketStock = [];
        foreach ($eventData->tickets as $ticket) {
            $ticketStock[$ticket->id] = $ticket->available_stock;
        }

        return view('events.show', [
            'event' => $eventData,
            'ticketStock' => $ticketStock,
        ]);
    }
}
