<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialPost extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'platforms',
        'content',
        'media_urls',
        'scheduled_at',
        'published_at',
        'status',
        'metrics',
        'created_by',
    ];

    protected $casts = [
        'platforms' => 'array',
        'media_urls' => 'array',
        'metrics' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    /**
     * Get the business that owns this post.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the user who created this post.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
