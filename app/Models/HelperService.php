<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelperService extends Model
{
    protected $fillable = ['helper_profile_id', 'service_category_id', 'rate'];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    public function helperProfile(): BelongsTo
    {
        return $this->belongsTo(HelperProfile::class);
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
