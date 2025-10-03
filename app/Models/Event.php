<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model implements HasMedia
{
    // Gunakan trait yang diperlukan
    use HasFactory, InteractsWithMedia, HasSlug, SoftDeletes;

    /**
     * Properti yang tidak bisa diisi secara massal (guard)
     */
    protected $guarded = ['id'];

    /**
     * Properti yang bisa diisi secara massal.
     * Disesuaikan dengan migrasi.
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'location',
        'is_online',
        'start_date',
        'end_date',
        'status',
        'has_numbered_seats',
    ];

    /**
     * Tipe data casting.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'has_numbered_seats' => 'boolean',
        'is_online' => 'boolean',
        'status' => EventStatus::class,
    ];

    /**
     * Konfigurasi untuk Spatie Media Library.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('event_posters')
             ->singleFile();
    }

    /**
     * Konfigurasi untuk Spatie Sluggable.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // --- RELASI --- //

    /**
     * Relasi ke User (pembuat event).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Tiket.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Relasi ke Kursi.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    // --- VALIDATION RULES --- //
    
    /**
     * Validation rules for Event
     */
    public static function validationRules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,published,completed,cancelled',
            'is_online' => 'boolean',
            'has_numbered_seats' => 'boolean',
        ];
    }

    // --- SCOPES --- //
    
    /**
     * Scope untuk event yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', EventStatus::PUBLISHED);
    }

    /**
     * Scope untuk event yang aktif (belum selesai)
     */
    public function scopeActive($query)
    {
        return $query->where('end_date', '>', now());
    }

    /**
     * Scope untuk event milik user tertentu
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
