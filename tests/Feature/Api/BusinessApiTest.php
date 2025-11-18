<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BusinessApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_businesses()
    {
        Business::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/businesses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'status',
                        'timezone',
                    ]
                ]
            ]);
    }

    public function test_can_create_business()
    {
        $data = [
            'name' => 'Restaurant Test',
            'type' => 'restaurant',
            'phone' => '+33123456789',
            'email' => 'test@restaurant.com',
            'address' => [
                'street' => '123 Rue de Test',
                'city' => 'Paris',
                'postal_code' => '75001',
                'country' => 'France',
            ],
            'timezone' => 'Europe/Paris',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/businesses', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Restaurant Test',
                'type' => 'restaurant',
            ]);

        $this->assertDatabaseHas('businesses', [
            'name' => 'Restaurant Test',
            'type' => 'restaurant',
        ]);
    }

    public function test_can_show_business()
    {
        $business = Business::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/businesses/{$business->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $business->id,
                'name' => $business->name,
            ]);
    }

    public function test_can_update_business()
    {
        $business = Business::factory()->create();

        $data = [
            'name' => 'Updated Restaurant Name',
            'status' => 'inactive',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/businesses/{$business->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Restaurant Name',
                'status' => 'inactive',
            ]);

        $this->assertDatabaseHas('businesses', [
            'id' => $business->id,
            'name' => 'Updated Restaurant Name',
            'status' => 'inactive',
        ]);
    }

    public function test_can_delete_business()
    {
        $business = Business::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/v1/businesses/{$business->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('businesses', [
            'id' => $business->id,
        ]);
    }

    public function test_requires_authentication()
    {
        $response = $this->getJson('/api/v1/businesses');

        $response->assertStatus(401);
    }

    public function test_validates_required_fields()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/businesses', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type', 'timezone']);
    }
}
