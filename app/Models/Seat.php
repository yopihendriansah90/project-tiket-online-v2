<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'ticket_id',
        'area',
        'row',
        'number',
        'is_available',
        'order_item_id',
        'reserved_until',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'reserved_until' => 'datetime',
    ];

    // Relasi
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function attendee()
    {
        // Kursi ini hanya dimiliki oleh satu Attendee
        return $this->hasOne(Attendee::class);
    }
}
