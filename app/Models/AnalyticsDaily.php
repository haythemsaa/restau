<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsDaily extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'date',
        'module',
        'metrics',
    ];

    protected $casts = [
        'date' => 'date',
        'metrics' => 'array',
    ];

    /**
     * Get the business that owns this analytics record.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
