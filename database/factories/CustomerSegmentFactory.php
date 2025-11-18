<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\CustomerSegment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSegment>
 */
class CustomerSegmentFactory extends Factory
{
    protected $model = CustomerSegment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->randomElement([
                'VIP',
                'Regulars',
                'At Risk',
                'New Customers',
                'High Spenders',
                'Frequent Visitors'
            ]),
            'description' => fake()->sentence(),
            'criteria' => [
                'lifetime_value_min' => fake()->randomElement([null, 100, 500, 1000]),
                'visit_count_min' => fake()->randomElement([null, 2, 5, 10]),
            ],
            'auto_update' => true,
            'customer_count' => 0,
        ];
    }

    /**
     * VIP segment.
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'VIP',
            'description' => 'High-value loyal customers',
            'criteria' => [
                'lifetime_value_min' => 500,
                'visit_count_min' => 10,
            ],
        ]);
    }

    /**
     * At Risk segment.
     */
    public function atRisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'At Risk',
            'description' => 'Customers who haven\'t visited recently',
            'criteria' => [
                'days_since_last_visit_min' => 60,
                'visit_count_min' => 2,
            ],
        ]);
    }
}
