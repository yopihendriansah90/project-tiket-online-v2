<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        return view('checkin.index');
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string|max:100',
            'gate' => 'nullable|string|max:50',
        ]);

        $attendee = Attendee::with(['seat', 'orderItem.ticket.event'])
            ->where('unique_token', $data['token'])
            ->first();

        if (!$attendee) {
            return response()->json([
                'ok' => false,
                'message' => 'Token tidak valid atau peserta tidak ditemukan.',
            ], 404);
        }

        if ($attendee->checked_in_at) {
            return response()->json([
                'ok' => false,
                'message' => 'Peserta sudah check-in.',
                'checked_in_at' => $attendee->checked_in_at,
                'gate' => $attendee->checkin_gate,
                'attendee' => [
                    'name' => $attendee->name,
                    'phone' => $attendee->phone,
                    'seat' => $attendee->seat_display,
                    'event' => optional($attendee->orderItem->ticket->event)->title,
                ],
            ], 409);
        }

        $attendee->checked_in_at = now();
        if (!empty($data['gate'])) {
            $attendee->checkin_gate = $data['gate'];
        }
        $attendee->save();

        return response()->json([
            'ok' => true,
            'message' => 'Check-in berhasil.',
            'attendee' => [
                'name' => $attendee->name,
                'phone' => $attendee->phone,
                'seat' => $attendee->seat_display,
                'event' => optional($attendee->orderItem->ticket->event)->title,
            ],
        ]);
    }
}