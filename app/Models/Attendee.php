<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_item_id',
        'user_id',
        'name',
        'email',
        'unique_token',
        'seat_id',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];
// Relasi BARU
    public function seat()
    {
        // Mendapatkan detail kursi (Baris & Nomor) peserta ini
        return $this->belongsTo(Seat::class);
    }
    // Relasi
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
