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
        'price',
        'quantity',
        'available_from',
        'available_to',
        'is_seated',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_seated' => 'boolean',
        'available_from' => 'datetime',
        'available_to' => 'datetime',
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
