<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvailabilitySlot extends Model
{
    protected $fillable = [
        'helper_profile_id', 'date', 'start_time', 'end_time', 'is_booked',
    ];

    protected $casts = [
        'date' => 'date',
        'is_booked' => 'boolean',
    ];

    public function helperProfile(): BelongsTo
    {
        return $this->belongsTo(HelperProfile::class);
    }
}
