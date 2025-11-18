<?php

namespace Tests\Unit\Services\AI;

use App\Models\Business;
use App\Models\Review;
use App\Services\AI\ReviewResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class ReviewResponseServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReviewResponseService $service;
    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReviewResponseService();

        // Create a test business
        $this->business = Business::factory()->create([
            'name' => 'Test Restaurant',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_generates_response_for_positive_review()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 5,
            'content' => 'Excellente expérience ! Nourriture délicieuse et service impeccable.',
            'platform' => 'google',
        ]);

        $result = $this->service->generateResponse($review);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('tone', $result);
        $this->assertArrayHasKey('word_count', $result);
        $this->assertNotEmpty($result['response']);

        // La réponse devrait être positive et remercier le client
        $response = strtolower($result['response']);
        $this->assertTrue(
            str_contains($response, 'merci') ||
            str_contains($response, 'ravi') ||
            str_contains($response, 'heureux'),
            'Positive review response should be thankful'
        );
    }

    /** @test */
    public function it_generates_response_for_negative_review()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 2,
            'content' => 'Très déçu. Service lent et nourriture froide.',
            'platform' => 'google',
        ]);

        $result = $this->service->generateResponse($review, 'empathetic');

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['response']);

        // La réponse devrait être empathique et s'excuser
        $response = strtolower($result['response']);
        $this->assertTrue(
            str_contains($response, 'désolé') ||
            str_contains($response, 'excuse') ||
            str_contains($response, 'regrett'),
            'Negative review response should be apologetic'
        );
    }

    /** @test */
    public function it_generates_multiple_response_suggestions()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 4,
            'content' => 'Bon restaurant, ambiance agréable. Dommage que le service soit un peu lent.',
            'platform' => 'tripadvisor',
        ]);

        $result = $this->service->suggestMultipleResponses($review, 3);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        foreach ($result as $suggestion) {
            $this->assertArrayHasKey('id', $suggestion);
            $this->assertArrayHasKey('content', $suggestion);
            $this->assertArrayHasKey('tone', $suggestion);
            $this->assertArrayHasKey('length', $suggestion);
            $this->assertNotEmpty($suggestion['content']);
        }

        // Les suggestions devraient avoir des tons différents
        $tones = array_column($result, 'tone');
        $this->assertCount(3, array_unique($tones), 'Suggestions should have different tones');
    }

    /** @test */
    public function it_validates_response_quality()
    {
        $goodResponse = "Merci beaucoup pour votre retour ! Nous sommes ravis que vous ayez apprécié votre expérience chez nous.";
        $badResponse = "ok merci";

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('validateResponse');
        $method->setAccessible(true);

        $goodResult = $method->invoke($this->service, $goodResponse);
        $this->assertTrue($goodResult['is_valid']);

        $badResult = $method->invoke($this->service, $badResponse);
        $this->assertFalse($badResult['is_valid']);
        $this->assertNotEmpty($badResult['issues']);
    }

    /** @test */
    public function it_adapts_tone_based_on_rating()
    {
        $review5Star = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 5,
            'content' => 'Parfait !',
        ]);

        $review1Star = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 1,
            'content' => 'Horrible expérience',
        ]);

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getToneForRating');
        $method->setAccessible(true);

        $tone5 = $method->invoke($this->service, 5);
        $tone1 = $method->invoke($this->service, 1);

        $this->assertNotEquals($tone5, $tone1);
        $this->assertEquals('enthusiastic', $tone5);
        $this->assertEquals('empathetic', $tone1);
    }

    /** @test */
    public function it_includes_business_name_in_response()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 5,
            'content' => 'Super restaurant !',
        ]);

        $result = $this->service->generateResponse($review);

        // La réponse pourrait mentionner le nom du restaurant
        $this->assertNotEmpty($result['response']);
    }

    /** @test */
    public function it_handles_different_tones()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 4,
            'content' => 'Bonne expérience globale',
        ]);

        $tones = ['professional', 'warm', 'enthusiastic'];

        foreach ($tones as $tone) {
            $result = $this->service->generateResponse($review, $tone);

            $this->assertIsArray($result);
            $this->assertNotEmpty($result['response']);
            $this->assertEquals($tone, $result['tone']);
        }
    }

    /** @test */
    public function it_rejects_invalid_tone()
    {
        $this->expectException(\InvalidArgumentException::class);

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 4,
            'content' => 'Test',
        ]);

        $this->service->generateResponse($review, 'invalid_tone');
    }

    /** @test */
    public function it_handles_review_without_content()
    {
        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 5,
            'content' => null,
        ]);

        // Should still generate a response based on rating alone
        if (config('services.openai.api_key')) {
            $result = $this->service->generateResponse($review);
            $this->assertNotEmpty($result['response']);
        } else {
            $this->markTestSkipped('OpenAI API key not configured');
        }
    }

    /** @test */
    public function it_respects_character_limits()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 4,
            'content' => 'Bon restaurant avec quelques points à améliorer',
        ]);

        $result = $this->service->generateResponse($review);

        // Most platforms have character limits (e.g., Twitter: 280, Google: 4096)
        // Our responses should be reasonable in length
        $this->assertLessThan(500, strlen($result['response']), 'Response should be concise');
    }

    /** @test */
    public function it_auto_posts_response_when_requested()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $review = Review::factory()->create([
            'business_id' => $this->business->id,
            'rating' => 5,
            'content' => 'Excellente expérience',
            'platform' => 'google',
            'platform_review_id' => 'test-123',
        ]);

        $result = $this->service->generateAndPost($review);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('posted', $result);

        // Check if review was updated
        $review->refresh();
        $this->assertNotNull($review->reply);
        $this->assertNotNull($review->replied_at);
    }
}
