<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'platform',
        'platform_review_id',
        'author_name',
        'rating',
        'text',
        'response_text',
        'response_date',
        'sentiment_score',
        'categories',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'sentiment_score' => 'decimal:2',
        'categories' => 'array',
        'response_date' => 'datetime',
    ];

    /**
     * Get the business that owns this review.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
