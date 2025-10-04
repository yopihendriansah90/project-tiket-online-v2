<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_price',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // --- VALIDATION RULES --- //
    
    public static function validationRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string|unique:orders,invoice_number|max:50',
            'total_price' => 'required|numeric|min:0|max:999999999.99',
            'status' => 'required|in:pending,paid,failed,cancelled',
            'payment_method' => 'nullable|string|max:100',
            'paid_at' => 'nullable|date',
        ];
    }

    // --- RELASI --- //
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    // --- SCOPES --- //
    
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // --- ACCESSORS --- //
    
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    // --- METHODS --- //
    
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $sequence = str_pad(self::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        
        return $prefix . '-' . $date . '-' . $sequence;
    }
}
