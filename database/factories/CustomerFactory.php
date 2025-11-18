<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->optional()->date('Y-m-d', '-18 years'),
            'preferences' => [
                'dietary' => fake()->randomElement([
                    [],
                    ['vegetarian'],
                    ['vegan'],
                    ['gluten-free'],
                    ['vegetarian', 'gluten-free']
                ]),
                'allergies' => fake()->randomElement([
                    [],
                    ['nuts'],
                    ['lactose'],
                    ['shellfish']
                ]),
                'favorite_dishes' => [],
            ],
            'tags' => fake()->randomElement([
                [],
                ['food-blogger'],
                ['regular'],
                ['corporate'],
                ['celebration']
            ]),
            'tier' => fake()->randomElement(['regular', 'regular', 'regular', 'vip', 'super_vip']),
            'lifetime_value' => fake()->randomFloat(2, 0, 2000),
            'visit_count' => fake()->numberBetween(0, 50),
            'last_visit_at' => fake()->optional()->dateTimeBetween('-6 months', 'now'),
            'language' => fake()->randomElement(['fr', 'en', 'es', 'it']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the customer is VIP.
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier' => 'vip',
            'lifetime_value' => fake()->randomFloat(2, 500, 1500),
            'visit_count' => fake()->numberBetween(10, 30),
        ]);
    }

    /**
     * Indicate that the customer is Super VIP.
     */
    public function superVip(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier' => 'super_vip',
            'lifetime_value' => fake()->randomFloat(2, 1000, 5000),
            'visit_count' => fake()->numberBetween(20, 100),
        ]);
    }

    /**
     * Indicate that the customer is at risk.
     */
    public function atRisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_visit_at' => fake()->dateTimeBetween('-6 months', '-60 days'),
            'visit_count' => fake()->numberBetween(3, 15),
        ]);
    }

    /**
     * Indicate that the customer is new.
     */
    public function new(): static
    {
        return $this->state(fn (array $attributes) => [
            'visit_count' => 1,
            'last_visit_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'lifetime_value' => fake()->randomFloat(2, 30, 150),
        ]);
    }
}
