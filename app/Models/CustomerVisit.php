<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVisit extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'customer_id',
        'business_id',
        'visited_at',
        'amount_spent',
        'party_size',
        'items_ordered',
        'satisfaction_score',
        'feedback',
        'source',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'items_ordered' => 'array',
        'amount_spent' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Boot method to update customer stats
     */
    protected static function booted()
    {
        static::created(function ($visit) {
            $visit->updateCustomerStats();
        });

        static::updated(function ($visit) {
            $visit->updateCustomerStats();
        });

        static::deleted(function ($visit) {
            $visit->updateCustomerStats();
        });
    }

    /**
     * Update customer statistics
     */
    public function updateCustomerStats(): void
    {
        $customer = $this->customer;

        if (!$customer) {
            return;
        }

        $customer->update([
            'visit_count' => $customer->visits()->count(),
            'last_visit_at' => $customer->visits()->latest('visited_at')->first()?->visited_at,
            'lifetime_value' => $customer->visits()->sum('amount_spent'),
        ]);

        // Auto-promote to VIP if eligible
        $customer->promoteToVIP();
    }

    /**
     * Scopes
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('visited_at', '>=', now()->subDays($days));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('visited_at', now()->year)
            ->whereMonth('visited_at', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('visited_at', now()->year);
    }

    /**
     * Accessors
     */
    public function getIsRecentAttribute(): bool
    {
        return $this->visited_at->isAfter(now()->subDays(30));
    }

    public function getWasPositiveAttribute(): bool
    {
        return $this->satisfaction_score >= 4;
    }
}
