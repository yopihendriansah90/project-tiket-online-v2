<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventDisplayController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::where('status', \App\Enums\EventStatus::PUBLISHED)
                       ->orderBy('start_date', 'asc')
                       ->paginate(12); // Ambil 12 event per halaman

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
