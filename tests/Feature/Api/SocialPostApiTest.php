<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\SocialPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SocialPostApiTest extends TestCase
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

    public function test_can_list_social_posts()
    {
        SocialPost::factory()->count(3)->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/social-posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'business_id',
                        'content',
                        'platforms',
                        'status',
                    ]
                ]
            ]);
    }

    public function test_can_filter_posts_by_status()
    {
        SocialPost::factory()->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
            'status' => 'draft',
        ]);

        SocialPost::factory()->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/social-posts?status=draft');

        $response->assertStatus(200);
        $data = $response->json('data');

        foreach ($data as $post) {
            $this->assertEquals('draft', $post['status']);
        }
    }

    public function test_can_create_social_post()
    {
        $data = [
            'business_id' => $this->business->id,
            'content' => 'Check out our new menu!',
            'platforms' => ['facebook', 'instagram'],
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/social-posts', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'content' => 'Check out our new menu!',
                'status' => 'draft',
            ]);

        $this->assertDatabaseHas('social_posts', [
            'business_id' => $this->business->id,
            'content' => 'Check out our new menu!',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_update_social_post()
    {
        $post = SocialPost::factory()->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
            'status' => 'draft',
        ]);

        $data = [
            'content' => 'Updated content',
            'status' => 'scheduled',
            'scheduled_for' => now()->addDay()->toIso8601String(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/social-posts/{$post->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'content' => 'Updated content',
                'status' => 'scheduled',
            ]);

        $this->assertDatabaseHas('social_posts', [
            'id' => $post->id,
            'content' => 'Updated content',
            'status' => 'scheduled',
        ]);
    }

    public function test_can_publish_social_post()
    {
        $post = SocialPost::factory()->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/social-posts/{$post->id}/publish");

        $response->assertStatus(200);

        // The post should be queued for publishing
        $this->assertDatabaseHas('social_posts', [
            'id' => $post->id,
        ]);
    }

    public function test_can_delete_social_post()
    {
        $post = SocialPost::factory()->for($this->business, 'business')->create([
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/v1/social-posts/{$post->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('social_posts', [
            'id' => $post->id,
        ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/social-posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['business_id', 'content', 'platforms']);
    }

    public function test_validates_platforms_array()
    {
        $data = [
            'business_id' => $this->business->id,
            'content' => 'Test content',
            'platforms' => 'not_an_array', // Invalid
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/social-posts', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['platforms']);
    }

    public function test_requires_authentication()
    {
        $response = $this->getJson('/api/v1/social-posts');

        $response->assertStatus(401);
    }
}
