<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia; // Kebutuhan Spatie Media Library
use Spatie\MediaLibrary\InteractsWithMedia; // Kebutuhan Spatie Media Library

class Event extends Model implements HasMedia // Implementasi interface HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia; // Implementasi SoftDeletes dan InteractsWithMedia
protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'is_online',
        'has_numbered_seats',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'has_numbered_seats' => 'boolean',
    ];

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Spatie Media Library - WAJIB untuk Filament
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('event_posters')
             ->singleFile();
    }
    // Relasi BARU
    public function seats()
    {
        return $this->hasMany(Seat::class); // Kursi di event ini
    }
        public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }



}
