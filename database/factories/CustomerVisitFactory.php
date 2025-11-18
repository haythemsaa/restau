<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerVisit>
 */
class CustomerVisitFactory extends Factory
{
    protected $model = CustomerVisit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'business_id' => Business::factory(),
            'visited_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'amount_spent' => fake()->randomFloat(2, 20, 300),
            'party_size' => fake()->numberBetween(1, 8),
            'items_ordered' => [
                fake()->randomElement([
                    'Entrée du jour',
                    'Salade César',
                    'Carpaccio de boeuf'
                ]),
                fake()->randomElement([
                    'Filet mignon',
                    'Saumon grillé',
                    'Risotto aux champignons',
                    'Burger maison'
                ]),
                fake()->optional()->randomElement([
                    'Tiramisu',
                    'Tarte Tatin',
                    'Mousse au chocolat'
                ])
            ],
            'satisfaction_score' => fake()->numberBetween(1, 5),
            'feedback' => fake()->optional()->sentence(),
            'source' => fake()->randomElement(['walk-in', 'reservation', 'delivery', 'takeaway']),
        ];
    }

    /**
     * Indicate that this was a great visit.
     */
    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'satisfaction_score' => 5,
            'amount_spent' => fake()->randomFloat(2, 100, 300),
            'feedback' => 'Excellent service et nourriture délicieuse!',
        ]);
    }

    /**
     * Indicate that this was a poor visit.
     */
    public function poor(): static
    {
        return $this->state(fn (array $attributes) => [
            'satisfaction_score' => fake()->numberBetween(1, 2),
            'amount_spent' => fake()->randomFloat(2, 20, 80),
            'feedback' => fake()->randomElement([
                'Service lent',
                'Plat froid',
                'Déçu de la qualité'
            ]),
        ]);
    }

    /**
     * Indicate this was a recent visit.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'visited_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}
