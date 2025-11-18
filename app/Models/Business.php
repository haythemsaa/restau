<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'group_id',
        'name',
        'legal_name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'metadata',
    ];

    protected $casts = [
        'address' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the group that owns this business.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(BusinessGroup::class, 'group_id');
    }

    /**
     * Get the users that have access to this business.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
            ->withPivot('role', 'permissions')
            ->withTimestamps();
    }

    /**
     * Get the reviews for this business.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the social posts for this business.
     */
    public function socialPosts(): HasMany
    {
        return $this->hasMany(SocialPost::class);
    }

    /**
     * Get the conversations for this business.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get the analytics for this business.
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(AnalyticsDaily::class);
    }
}
