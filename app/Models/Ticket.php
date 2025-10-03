<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory; // Implementasi SoftDeletes

    protected $guarded = ['id'];

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity',
        'available_from',
        'available_to',
        'is_seated',
        'seat_area',
        'is_seating_enabled',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_seated' => 'boolean',
        'is_seating_enabled' => 'boolean',
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

    // --- VALIDATION RULES --- //
    
    public static function validationRules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255|min:3',
            'price' => 'required|numeric|min:0|max:999999999',
            'quantity' => 'nullable|integer|min:1|max:999999',
            'available_from' => 'required|date',
            'available_to' => 'required|date|after:available_from',
            'is_seated' => 'boolean',
            'seat_area' => 'nullable|string|max:100',
            'is_seating_enabled' => 'boolean',
        ];
    }

    // --- SCOPES --- //
    
    public function scopeAvailable($query)
    {
        return $query->where('available_from', '<=', now())
                    ->where('available_to', '>=', now());
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    // --- ACCESSORS --- //
    
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getAvailableStockAttribute()
    {
        $soldTickets = $this->orderItems()
            ->whereHas('order', fn($q) => $q->whereIn('status', ['paid', 'pending']))
            ->sum('quantity');
        
        return max(0, $this->quantity - $soldTickets);
    }
}
