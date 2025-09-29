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
        // 1. Ambil input dari user
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest'); // Default sort 'latest'

        // 2. Mulai query builder dengan filter status default
        $query = Event::where('status', \App\Enums\EventStatus::PUBLISHED);

        // 3. Terapkan filter pencarian jika ada
        $query->when($search, function ($q, $search) {
            return $q->where(function($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
            });
        });

        // 4. Terapkan sorting
        switch ($sort) {
            case 'soonest':
                $query->orderBy('start_date', 'asc');
                break;
            case 'cheapest':
                $query->withMin('tickets', 'price')->orderBy('tickets_min_price', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 5. Ambil hasil dengan pagination
        $events = $query->paginate(12)->appends($request->query());

        // 6. Kirim data ke view
        return view('events.index', compact('events'));
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

        // Load relasi yang dibutuhkan
        $event->load('tickets');

        return view('events.show', compact('event'));
    }
}
