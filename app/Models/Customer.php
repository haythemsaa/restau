<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'email',
        'phone',
        'first_name',
        'last_name',
        'birth_date',
        'preferences',
        'tags',
        'tier',
        'lifetime_value',
        'visit_count',
        'last_visit_at',
        'language',
        'notes',
    ];

    protected $casts = [
        'preferences' => 'array',
        'tags' => 'array',
        'birth_date' => 'date',
        'last_visit_at' => 'datetime',
        'lifetime_value' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function visits()
    {
        return $this->hasMany(CustomerVisit::class);
    }

    public function segments()
    {
        return $this->belongsToMany(CustomerSegment::class, 'customer_customer_segment')
            ->withTimestamps();
    }

    /**
     * Business Logic Methods
     */
    public function calculateLifetimeValue(): float
    {
        return $this->visits()->sum('amount_spent');
    }

    public function updateLifetimeValue(): void
    {
        $this->update([
            'lifetime_value' => $this->calculateLifetimeValue()
        ]);
    }

    public function getRFMScore(): array
    {
        $recency = $this->last_visit_at?->diffInDays(now()) ?? 999;
        $frequency = $this->visit_count;
        $monetary = $this->lifetime_value;

        return [
            'recency' => $this->scoreRecency($recency),
            'frequency' => $this->scoreFrequency($frequency),
            'monetary' => $this->scoreMonetary($monetary),
            'total_score' => $this->calculateTotalScore($recency, $frequency, $monetary),
            'segment' => $this->getRFMSegment($recency, $frequency, $monetary),
        ];
    }

    private function scoreRecency(int $days): int
    {
        if ($days <= 30) return 5;
        if ($days <= 60) return 4;
        if ($days <= 90) return 3;
        if ($days <= 180) return 2;
        return 1;
    }

    private function scoreFrequency(int $visits): int
    {
        if ($visits >= 20) return 5;
        if ($visits >= 10) return 4;
        if ($visits >= 5) return 3;
        if ($visits >= 2) return 2;
        return 1;
    }

    private function scoreMonetary(float $amount): int
    {
        if ($amount >= 1000) return 5;
        if ($amount >= 500) return 4;
        if ($amount >= 250) return 3;
        if ($amount >= 100) return 2;
        return 1;
    }

    private function calculateTotalScore(int $recency, int $frequency, float $monetary): float
    {
        return ($this->scoreRecency($recency) + $this->scoreFrequency($frequency) + $this->scoreMonetary($monetary)) / 3;
    }

    private function getRFMSegment(int $recency, int $frequency, float $monetary): string
    {
        $rScore = $this->scoreRecency($recency);
        $fScore = $this->scoreFrequency($frequency);
        $mScore = $this->scoreMonetary($monetary);

        if ($rScore >= 4 && $fScore >= 4 && $mScore >= 4) {
            return 'Champions';
        } elseif ($rScore >= 3 && $fScore >= 3) {
            return 'Loyal Customers';
        } elseif ($rScore >= 4) {
            return 'Recent Customers';
        } elseif ($mScore >= 4) {
            return 'Big Spenders';
        } elseif ($rScore <= 2 && $fScore >= 3) {
            return 'At Risk';
        } elseif ($rScore <= 2) {
            return 'Lost';
        }

        return 'Potential';
    }

    public function isAtRiskOfChurn(): bool
    {
        if (!$this->last_visit_at) {
            return false;
        }

        $daysSinceLastVisit = $this->last_visit_at->diffInDays(now());
        $averageVisitInterval = $this->calculateAverageVisitInterval();

        // At risk if 2x the average interval has passed
        return $daysSinceLastVisit > ($averageVisitInterval * 2);
    }

    public function calculateAverageVisitInterval(): int
    {
        if ($this->visit_count <= 1) {
            return 60; // Default 60 days
        }

        $firstVisit = $this->visits()->oldest('visited_at')->first();
        $lastVisit = $this->visits()->latest('visited_at')->first();

        if (!$firstVisit || !$lastVisit) {
            return 60;
        }

        $totalDays = $firstVisit->visited_at->diffInDays($lastVisit->visited_at);

        return (int) ($totalDays / max($this->visit_count - 1, 1));
    }

    public function promoteToVIP(): bool
    {
        if ($this->tier === 'super_vip') {
            return false; // Already at max tier
        }

        $rfm = $this->getRFMScore();

        if ($this->lifetime_value >= 500 && $this->visit_count >= 10 && $rfm['total_score'] >= 4) {
            $this->update(['tier' => 'vip']);
            return true;
        }

        if ($this->lifetime_value >= 1000 && $this->visit_count >= 20 && $rfm['total_score'] >= 4.5) {
            $this->update(['tier' => 'super_vip']);
            return true;
        }

        return false;
    }

    public function isBirthday(): bool
    {
        if (!$this->birth_date) {
            return false;
        }

        return $this->birth_date->isBirthday();
    }

    public function getBirthdayMonth(): ?int
    {
        return $this->birth_date?->month;
    }

    /**
     * Scopes
     */
    public function scopeVip($query)
    {
        return $query->whereIn('tier', ['vip', 'super_vip']);
    }

    public function scopeAtRisk($query)
    {
        return $query->where('last_visit_at', '<', now()->subDays(60));
    }

    public function scopeActive($query)
    {
        return $query->where('last_visit_at', '>=', now()->subDays(90));
    }

    public function scopeBirthdayThisMonth($query)
    {
        return $query->whereMonth('birth_date', now()->month);
    }

    /**
     * Accessors & Mutators
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getIsVipAttribute(): bool
    {
        return in_array($this->tier, ['vip', 'super_vip']);
    }

    public function getAverageSpendAttribute(): float
    {
        if ($this->visit_count === 0) {
            return 0;
        }

        return round($this->lifetime_value / $this->visit_count, 2);
    }
}
