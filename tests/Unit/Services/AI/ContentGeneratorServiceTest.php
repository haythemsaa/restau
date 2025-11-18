<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\ContentGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class ContentGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContentGeneratorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContentGeneratorService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_generates_social_media_post_for_instagram()
    {
        // Skip if OpenAI key not configured
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $params = [
            'business_id' => 'test-business',
            'platform' => 'instagram',
            'theme' => 'Nouveau menu automne',
            'tone' => 'enthousiaste',
            'audience' => 'jeunes_adultes',
        ];

        $result = $this->service->generateSocialPost($params);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('hashtags', $result);
        $this->assertArrayHasKey('word_count', $result);
        $this->assertArrayHasKey('best_time', $result);
        $this->assertNotEmpty($result['content']);
        $this->assertIsArray($result['hashtags']);
    }

    /** @test */
    public function it_extracts_hashtags_from_content()
    {
        $content = "Découvrez notre nouveau menu #Automne2024 #RestaurantParis #GastronomiesFrançaise";

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('extractHashtags');
        $method->setAccessible(true);

        $hashtags = $method->invoke($this->service, $content);

        $this->assertIsArray($hashtags);
        $this->assertContains('#Automne2024', $hashtags);
        $this->assertContains('#RestaurantParis', $hashtags);
        $this->assertContains('#GastronomiesFrançaise', $hashtags);
    }

    /** @test */
    public function it_returns_best_posting_time_for_different_platforms()
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getBestPostingTime');
        $method->setAccessible(true);

        $instagramTime = $method->invoke($this->service, 'instagram');
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $instagramTime);

        $facebookTime = $method->invoke($this->service, 'facebook');
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $facebookTime);

        $twitterTime = $method->invoke($this->service, 'twitter');
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $twitterTime);
    }

    /** @test */
    public function it_generates_multiple_variations()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $params = [
            'business_id' => 'test-business',
            'platform' => 'facebook',
            'theme' => 'Happy Hour',
            'count' => 3,
        ];

        $result = $this->service->generateMultipleVariations($params);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('variations', $result);
        $this->assertCount(3, $result['variations']);

        foreach ($result['variations'] as $variation) {
            $this->assertArrayHasKey('id', $variation);
            $this->assertArrayHasKey('content', $variation);
            $this->assertArrayHasKey('hashtags', $variation);
        }
    }

    /** @test */
    public function it_suggests_relevant_hashtags()
    {
        if (!config('services.openai.api_key')) {
            $this->markTestSkipped('OpenAI API key not configured');
        }

        $params = [
            'business_id' => 'test-business',
            'content' => 'Venez déguster notre nouveau burger végétarien !',
            'platform' => 'instagram',
        ];

        $result = $this->service->suggestHashtags($params);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('hashtags', $result);
        $this->assertArrayHasKey('popular', $result);
        $this->assertArrayHasKey('trending', $result);
        $this->assertIsArray($result['hashtags']);
    }

    /** @test */
    public function it_validates_content_before_generation()
    {
        $this->expectException(\InvalidArgumentException::class);

        $params = [
            'business_id' => 'test-business',
            'platform' => 'invalid_platform',
            'theme' => 'Test',
        ];

        $this->service->generateSocialPost($params);
    }

    /** @test */
    public function it_handles_missing_required_parameters()
    {
        $this->expectException(\InvalidArgumentException::class);

        $params = [
            'business_id' => 'test-business',
            // Missing platform and theme
        ];

        $this->service->generateSocialPost($params);
    }

    /** @test */
    public function it_builds_prompt_correctly()
    {
        $params = [
            'business_id' => 'test-business',
            'platform' => 'instagram',
            'theme' => 'Nouveau menu',
            'tone' => 'professionnel',
            'audience' => 'familles',
            'details' => 'Menu italien avec pâtes fraîches',
        ];

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('buildPrompt');
        $method->setAccessible(true);

        $prompt = $method->invoke($this->service, $params);

        $this->assertStringContainsString('instagram', strtolower($prompt));
        $this->assertStringContainsString('nouveau menu', strtolower($prompt));
        $this->assertStringContainsString('professionnel', strtolower($prompt));
        $this->assertStringContainsString('familles', strtolower($prompt));
    }
}
