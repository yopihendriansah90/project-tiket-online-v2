<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
   use HasFactory; // Implementasi SoftDeletes

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'seat_area', // <- TAMBAHKAN
        'is_seating_enabled',
        'available_from',
        'available_to',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'available_from' => 'datetime',
        'available_to' => 'datetime',
         'is_seating_enabled' => 'boolean', 
    ];

    // Relasi
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Relasi BARU
    public function seats()
    {
        return $this->hasMany(Seat::class); // Kursi yang terkait dengan jenis tiket ini
    }
}
