<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Business $business;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->business = Business::factory()->create();
    }

    public function test_can_list_reviews()
    {
        Review::factory()->count(3)->for($this->business)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/reviews');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'business_id',
                        'platform',
                        'author_name',
                        'rating',
                        'text',
                    ]
                ]
            ]);
    }

    public function test_can_filter_reviews_by_platform()
    {
        Review::factory()->for($this->business)->create(['platform' => 'google']);
        Review::factory()->for($this->business)->create(['platform' => 'facebook']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/reviews?platform=google');

        $response->assertStatus(200);
        $data = $response->json('data');

        foreach ($data as $review) {
            $this->assertEquals('google', $review['platform']);
        }
    }

    public function test_can_filter_reviews_by_rating()
    {
        Review::factory()->for($this->business)->create(['rating' => 5]);
        Review::factory()->for($this->business)->create(['rating' => 3]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/reviews?rating=5');

        $response->assertStatus(200);
        $data = $response->json('data');

        foreach ($data as $review) {
            $this->assertEquals(5, $review['rating']);
        }
    }

    public function test_can_create_review()
    {
        $data = [
            'business_id' => $this->business->id,
            'platform' => 'google',
            'platform_review_id' => 'google_123',
            'author_name' => 'John Doe',
            'rating' => 4.5,
            'text' => 'Great restaurant!',
            'published_at' => now()->toIso8601String(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/reviews', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'author_name' => 'John Doe',
                'rating' => 4.5,
            ]);

        $this->assertDatabaseHas('reviews', [
            'business_id' => $this->business->id,
            'author_name' => 'John Doe',
        ]);
    }

    public function test_can_reply_to_review()
    {
        $review = Review::factory()->for($this->business)->create();

        $data = [
            'reply' => 'Thank you for your feedback!',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/reviews/{$review->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'reply' => 'Thank you for your feedback!',
            ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'reply' => 'Thank you for your feedback!',
        ]);

        $this->assertNotNull($review->fresh()->replied_at);
    }

    public function test_validates_rating_range()
    {
        $data = [
            'business_id' => $this->business->id,
            'platform' => 'google',
            'platform_review_id' => 'google_123',
            'author_name' => 'John Doe',
            'rating' => 6, // Invalid: rating should be between 0 and 5
            'published_at' => now()->toIso8601String(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/reviews', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_requires_authentication()
    {
        $response = $this->getJson('/api/v1/reviews');

        $response->assertStatus(401);
    }
}
