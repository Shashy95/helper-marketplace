<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationDocument extends Model
{
    protected $fillable = [
        'helper_profile_id', 'type', 'file_path', 'status', 'rejection_reason',
    ];

    public function helperProfile(): BelongsTo
    {
        return $this->belongsTo(HelperProfile::class);
    }
}
