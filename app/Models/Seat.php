<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

    // --- VALIDATION RULES --- //
    
    public static function validationRules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'area' => 'required|string|max:50',
            'row' => 'required|string|max:10',
            'number' => 'required|integer|min:1|max:9999',
            'is_available' => 'boolean',
            'order_item_id' => 'nullable|exists:order_items,id',
            'reserved_until' => 'nullable|date|after:now',
        ];
    }

    // --- RELASI --- //
    
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

    // --- SCOPES --- //
    
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where(function($q) {
                        $q->whereNull('reserved_until')
                          ->orWhere('reserved_until', '<', now());
                    });
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeInArea($query, $area)
    {
        return $query->where('area', $area);
    }

    // --- ACCESSORS --- //
    
    public function getSeatLabelAttribute()
    {
        return $this->area . '-' . $this->row . '-' . str_pad($this->number, 2, '0', STR_PAD_LEFT);
    }

    public function getIsExpiredAttribute()
    {
        return $this->reserved_until && $this->reserved_until < now();
    }

    // --- METHODS --- //
    
    public function reserve($minutes = 15): bool
    {
        if (!$this->is_available) {
            return false;
        }

        $this->update([
            'is_available' => false,
            'reserved_until' => now()->addMinutes($minutes)
        ]);

        return true;
    }

    public function release(): bool
    {
        return $this->update([
            'is_available' => true,
            'reserved_until' => null,
            'order_item_id' => null
        ]);
    }
}
