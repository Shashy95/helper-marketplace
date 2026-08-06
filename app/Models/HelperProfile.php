<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HelperProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'hourly_rate', 'latitude', 'longitude',
        'service_radius_km', 'verification_status', 'rating_avg',
        'rating_count', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'hourly_rate' => 'decimal:2',
        'rating_avg' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(HelperService::class);
    }

    public function serviceCategories(): HasManyThrough
    {
        return $this->hasManyThrough(
            ServiceCategory::class,
            HelperService::class,
            'helper_profile_id',
            'id',
            'id',
            'service_category_id'
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Only active, approved helpers are ever eligible to show up in discovery.
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('verification_status', 'approved');
    }

    // Recompute after every new review; call from the review-creation flow.
    public function recalculateRating(): void
    {
        $agg = $this->bookings()
            ->whereNotNull('rating')
            ->selectRaw('avg(rating) as avg_rating, count(rating) as total')
            ->first();

        $this->update([
            'rating_avg' => $agg->avg_rating ?? 0,
            'rating_count' => $agg->total ?? 0,
        ]);
    }
}
