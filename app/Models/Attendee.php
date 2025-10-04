<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'order_item_id',
        'user_id',
        'name',
        'email',
        'phone',
        'unique_token',
        'seat_id',
        'checked_in_at',
        'checkin_gate',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // --- VALIDATION RULES --- //
    
    public static function validationRules(): array
    {
        return [
            'order_item_id' => 'required|exists:order_items,id',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255|min:2',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'unique_token' => 'required|string|unique:attendees,unique_token|max:100',
            'seat_id' => 'nullable|exists:seats,id',
            'checked_in_at' => 'nullable|date',
            'checkin_gate' => 'nullable|string|max:50',
        ];
    }

    // --- RELASI --- //
    
    public function seat()
    {
        // Mendapatkan detail kursi (Baris & Nomor) peserta ini
        return $this->belongsTo(Seat::class);
    }
    
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- SCOPES --- //
    
    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('checked_in_at');
    }

    public function scopeNotCheckedIn($query)
    {
        return $query->whereNull('checked_in_at');
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->whereHas('orderItem.ticket.event', function($q) use ($eventId) {
            $q->where('id', $eventId);
        });
    }

    // --- ACCESSORS --- //
    
    public function getIsCheckedInAttribute()
    {
        return !is_null($this->checked_in_at);
    }

    public function getSeatDisplayAttribute()
    {
        return $this->seat ? $this->seat->seat_label : 'General Admission';
    }

    // --- METHODS --- //
    
    public static function generateUniqueToken(): string
    {
        do {
            $token = strtoupper(bin2hex(random_bytes(8))); // 16 character token
        } while (self::where('unique_token', $token)->exists());

        return $token;
    }

    public function checkIn(): bool
    {
        if ($this->is_checked_in) {
            return false;
        }

        return $this->update(['checked_in_at' => now()]);
    }
}
