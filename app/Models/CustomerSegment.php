<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'criteria',
        'auto_update',
        'customer_count',
    ];

    protected $casts = [
        'criteria' => 'array',
        'auto_update' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_customer_segment')
            ->withPivot('assigned_at');
    }

    /**
     * Update customer count
     */
    public function updateCustomerCount(): void
    {
        $this->update([
            'customer_count' => $this->customers()->count()
        ]);
    }

    /**
     * Check if customer meets segment criteria
     */
    public function meetsCriteria(Customer $customer): bool
    {
        $criteria = $this->criteria;

        // Check lifetime value
        if (isset($criteria['lifetime_value_min']) && $customer->lifetime_value < $criteria['lifetime_value_min']) {
            return false;
        }

        if (isset($criteria['lifetime_value_max']) && $customer->lifetime_value > $criteria['lifetime_value_max']) {
            return false;
        }

        // Check visit count
        if (isset($criteria['visit_count_min']) && $customer->visit_count < $criteria['visit_count_min']) {
            return false;
        }

        if (isset($criteria['visit_count_max']) && $customer->visit_count > $criteria['visit_count_max']) {
            return false;
        }

        // Check days since last visit
        if (isset($criteria['days_since_last_visit_min']) && $customer->last_visit_at) {
            $daysSince = $customer->last_visit_at->diffInDays(now());
            if ($daysSince < $criteria['days_since_last_visit_min']) {
                return false;
            }
        }

        if (isset($criteria['days_since_last_visit_max']) && $customer->last_visit_at) {
            $daysSince = $customer->last_visit_at->diffInDays(now());
            if ($daysSince > $criteria['days_since_last_visit_max']) {
                return false;
            }
        }

        // Check tier
        if (isset($criteria['tiers']) && !in_array($customer->tier, $criteria['tiers'])) {
            return false;
        }

        // Check tags
        if (isset($criteria['tags']) && $customer->tags) {
            $hasRequiredTags = !empty(array_intersect($criteria['tags'], $customer->tags));
            if (!$hasRequiredTags) {
                return false;
            }
        }

        return true;
    }

    /**
     * Auto-assign customers based on criteria
     */
    public function autoAssignCustomers(): int
    {
        if (!$this->auto_update) {
            return 0;
        }

        $customers = Customer::all();
        $assigned = 0;

        foreach ($customers as $customer) {
            if ($this->meetsCriteria($customer)) {
                $this->customers()->syncWithoutDetaching([$customer->id]);
                $assigned++;
            } else {
                $this->customers()->detach($customer->id);
            }
        }

        $this->updateCustomerCount();

        return $assigned;
    }

    /**
     * Common predefined segments
     */
    public static function createPredefinedSegments(Business $business): void
    {
        // VIP Customers
        self::create([
            'business_id' => $business->id,
            'name' => 'VIP',
            'description' => 'High-value loyal customers',
            'criteria' => [
                'lifetime_value_min' => 500,
                'visit_count_min' => 10,
            ],
            'auto_update' => true,
        ]);

        // At Risk
        self::create([
            'business_id' => $business->id,
            'name' => 'At Risk',
            'description' => 'Customers who haven\'t visited recently',
            'criteria' => [
                'days_since_last_visit_min' => 60,
                'visit_count_min' => 2,
            ],
            'auto_update' => true,
        ]);

        // New Customers
        self::create([
            'business_id' => $business->id,
            'name' => 'New Customers',
            'description' => 'Recent first-time customers',
            'criteria' => [
                'visit_count_max' => 1,
                'days_since_last_visit_max' => 30,
            ],
            'auto_update' => true,
        ]);

        // Frequent Visitors
        self::create([
            'business_id' => $business->id,
            'name' => 'Frequent Visitors',
            'description' => 'Customers who visit regularly',
            'criteria' => [
                'visit_count_min' => 5,
                'days_since_last_visit_max' => 30,
            ],
            'auto_update' => true,
        ]);
    }
}
