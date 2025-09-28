<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'status',
        'slug',
        'location',
        'start_date',
        'end_date',
        'description',
        'has_numbered_seats'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'has_numbered_seats' => 'boolean',
        'status' => EventStatus::class
    ];
    // Spatie Media Library - WAJIB untuk Filament
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('event_posters')
             ->singleFile();
    }

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}