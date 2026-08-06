<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    // Valid forward transitions. Enforced in BookingService, not here —
    // keep the model dumb, put the rules in one place.
    public const TRANSITIONS = [
        'requested' => ['accepted', 'declined', 'cancelled'],
        'accepted' => ['in_progress', 'cancelled'],
        'in_progress' => ['completed', 'cancelled'],
        'declined' => [],
        'completed' => [],
        'cancelled' => [],
    ];

    protected $fillable = [
        'client_id', 'helper_profile_id', 'service_category_id',
        'requested_date', 'requested_time', 'latitude', 'longitude',
        'address_note', 'agreed_price', 'status',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'agreed_price' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function helperProfile(): BelongsTo
    {
        return $this->belongsTo(HelperProfile::class);
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? [], true);
    }
}
