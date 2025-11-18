<?php

namespace Tests\Feature\AI;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_generates_social_media_content()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-content', [
                'business_id' => $this->business->id,
                'platform' => 'instagram',
                'theme' => 'Nouveau menu automne',
                'tone' => 'enthusiastic',
                'audience' => 'jeunes_adultes',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'content',
                'hashtags',
                'word_count',
                'best_time',
            ]);
    }

    /** @test */
    public function it_validates_required_fields_for_content_generation()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-content', [
                'business_id' => $this->business->id,
                // Missing platform and theme
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['platform', 'theme']);
    }

    /** @test */
    public function it_generates_multiple_content_variations()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-variations', [
                'business_id' => $this->business->id,
                'platform' => 'facebook',
                'theme' => 'Happy Hour',
                'count' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'variations' => [
                    '*' => ['id', 'content', 'hashtags'],
                ],
            ])
            ->assertJsonCount(3, 'variations');
    }

    /** @test */
    public function it_suggests_hashtags_for_content()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/suggest-hashtags', [
                'business_id' => $this->business->id,
                'content' => 'Découvrez notre nouveau burger végétarien fait maison !',
                'platform' => 'instagram',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'hashtags',
                'popular',
                'trending',
            ]);
    }

    /** @test */
    public function it_analyzes_sentiment_of_text()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/analyze-sentiment', [
                'text' => 'Excellente expérience ! Nourriture délicieuse et service impeccable.',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'overall_sentiment',
                'aspects',
                'emotions',
                'priority',
                'insights',
                'recommended_action',
            ]);

        // Positive sentiment should have high score
        $this->assertGreaterThan(0.5, $response->json('overall_sentiment'));
    }

    /** @test */
    public function it_analyzes_review_and_saves_results()
    {
        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'content' => 'Super restaurant ! Plats excellents mais service un peu lent.',
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/ai/reviews/{$review->id}/analyze");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'review' => [
                    'id',
                    'sentiment_score',
                    'sentiment_aspects',
                    'emotions',
                    'priority',
                    'ai_insights',
                ],
                'analysis' => [
                    'overall_sentiment',
                    'aspects',
                    'emotions',
                ],
            ]);

        // Check if review was updated in database
        $review->refresh();
        $this->assertNotNull($review->sentiment_score);
        $this->assertNotNull($review->sentiment_aspects);
        $this->assertNotNull($review->emotions);
        $this->assertNotNull($review->priority);
    }

    /** @test */
    public function it_generates_single_review_response()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'content' => 'Excellente expérience culinaire !',
            'rating' => 5,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/ai/reviews/{$review->id}/generate-response", [
                'tone' => 'warm',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'response',
                'tone',
                'word_count',
                'validation',
            ]);
    }

    /** @test */
    public function it_suggests_multiple_review_responses()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'content' => 'Bon restaurant avec quelques points à améliorer',
            'rating' => 3,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/ai/reviews/{$review->id}/suggest-responses", [
                'count' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'suggestions' => [
                    '*' => ['id', 'content', 'tone', 'length'],
                ],
            ])
            ->assertJsonCount(3, 'suggestions');
    }

    /** @test */
    public function it_auto_replies_to_review()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'content' => 'Très satisfait de ma visite',
            'rating' => 5,
            'platform' => 'google',
            'platform_review_id' => 'test-123',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/ai/reviews/{$review->id}/auto-reply", [
                'tone' => 'professional',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'response',
                'posted',
            ]);

        // Check if review was updated
        $review->refresh();
        $this->assertNotNull($review->reply);
        $this->assertNotNull($review->replied_at);
    }

    /** @test */
    public function it_requires_authentication_for_ai_endpoints()
    {
        $response = $this->postJson('/api/v1/ai/generate-content', [
            'business_id' => $this->business->id,
            'platform' => 'instagram',
            'theme' => 'Test',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_business_ownership()
    {
        $otherUser = User::factory()->create();
        $otherBusiness = Business::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-content', [
                'business_id' => $otherBusiness->id,
                'platform' => 'instagram',
                'theme' => 'Test',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_handles_api_errors_gracefully()
    {
        // Test with invalid API key
        config(['services.openai.api_key' => 'invalid-key']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-content', [
                'business_id' => $this->business->id,
                'platform' => 'instagram',
                'theme' => 'Test',
            ]);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Une erreur est survenue lors de la génération du contenu',
            ]);
    }

    /** @test */
    public function it_validates_platform_values()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/ai/generate-content', [
                'business_id' => $this->business->id,
                'platform' => 'invalid_platform',
                'theme' => 'Test',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['platform']);
    }

    /** @test */
    public function it_validates_tone_values()
    {
        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'content' => 'Test',
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/ai/reviews/{$review->id}/generate-response", [
                'tone' => 'invalid_tone',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tone']);
    }
}
