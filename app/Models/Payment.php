<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory, \Spatie\MediaLibrary\InteractsWithMedia;

    protected $fillable = [
        'order_id',
        'channel',
        'amount',
        'proof_path',
        'status',
        'notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public const CHANNELS = ['bri', 'mandiri', 'bca', 'dana', 'ovo', 'gopay', 'shopeepay'];
    public const STATUSES = ['submitted', 'verified', 'rejected'];

    // Relasi
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getChannelLabelAttribute(): string
    {
        return match ($this->channel) {
            'bri' => 'BRI',
            'mandiri' => 'Mandiri',
            'bca' => 'BCA',
            'dana' => 'Dana',
            'ovo' => 'OVO',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            default => ucfirst($this->channel),
        };
    }

    // Spatie Media Library collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_proofs')
            ->singleFile();
    }

    // Convenient accessor to get proof URL
    public function getProofUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('payment_proofs');
        return $media ? $media->getUrl() : null;
    }
}