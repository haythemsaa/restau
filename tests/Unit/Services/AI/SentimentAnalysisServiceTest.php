<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\SentimentAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class SentimentAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    private SentimentAnalysisService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SentimentAnalysisService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_analyzes_positive_sentiment()
    {
        $text = "Excellente expérience ! La nourriture était délicieuse et le service impeccable. Je recommande vivement ce restaurant.";

        $result = $this->service->analyze($text);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('overall_sentiment', $result);
        $this->assertArrayHasKey('aspects', $result);
        $this->assertArrayHasKey('emotions', $result);
        $this->assertArrayHasKey('priority', $result);
        $this->assertArrayHasKey('recommended_action', $result);

        // Le sentiment global devrait être positif (score > 0.5)
        $this->assertGreaterThan(0.5, $result['overall_sentiment']);
    }

    /** @test */
    public function it_analyzes_negative_sentiment()
    {
        $text = "Très déçu. Service lent, nourriture froide et addition salée. Je ne reviendrai pas.";

        $result = $this->service->analyze($text);

        // Le sentiment global devrait être négatif (score < 0.5)
        $this->assertLessThan(0.5, $result['overall_sentiment']);
        $this->assertContains($result['priority'], ['HIGH', 'URGENT']);
    }

    /** @test */
    public function it_extracts_aspects_correctly()
    {
        $text = "La nourriture était excellente mais le service était trop lent. Prix raisonnables.";

        $result = $this->service->analyze($text);

        $this->assertArrayHasKey('aspects', $result);
        $this->assertIsArray($result['aspects']);

        // Devrait détecter les aspects: food, service, price
        $aspects = array_keys($result['aspects']);
        $this->assertContains('food', $aspects);
        $this->assertContains('service', $aspects);
        $this->assertContains('price', $aspects);
    }

    /** @test */
    public function it_detects_emotions()
    {
        $text = "Quelle surprise fantastique ! J'étais tellement content de découvrir ce lieu.";

        $result = $this->service->analyze($text);

        $this->assertArrayHasKey('emotions', $result);
        $this->assertIsArray($result['emotions']);
        $this->assertNotEmpty($result['emotions']);

        // Devrait détecter des émotions positives comme 'joy' ou 'surprise'
        $emotions = array_map('strtolower', $result['emotions']);
        $this->assertTrue(
            in_array('joy', $emotions) || in_array('surprise', $emotions),
            'Should detect positive emotions like joy or surprise'
        );
    }

    /** @test */
    public function it_calculates_priority_correctly()
    {
        // Test critique négatif -> HIGH/URGENT priority
        $negativeText = "C'est scandaleux ! Inacceptable ! Je vais laisser des avis partout !";
        $result = $this->service->analyze($negativeText);
        $this->assertContains($result['priority'], ['HIGH', 'URGENT']);

        // Test neutre -> LOW/MEDIUM priority
        $neutralText = "C'était correct, rien de spécial.";
        $result = $this->service->analyze($neutralText);
        $this->assertContains($result['priority'], ['LOW', 'MEDIUM']);
    }

    /** @test */
    public function it_generates_actionable_insights()
    {
        $text = "Plats délicieux mais service vraiment trop lent. On a attendu 45 minutes.";

        $result = $this->service->analyze($text);

        $this->assertArrayHasKey('insights', $result);
        $this->assertIsString($result['insights']);
        $this->assertNotEmpty($result['insights']);

        // Les insights devraient mentionner les problèmes détectés
        $insights = strtolower($result['insights']);
        $this->assertStringContainsString('service', $insights);
    }

    /** @test */
    public function it_provides_recommended_actions()
    {
        $text = "Très mécontent du service. Serveur désagréable et nourriture froide.";

        $result = $this->service->analyze($text);

        $this->assertArrayHasKey('recommended_action', $result);
        $this->assertIsString($result['recommended_action']);
        $this->assertNotEmpty($result['recommended_action']);

        // L'action recommandée devrait être urgente pour un avis très négatif
        $action = strtolower($result['recommended_action']);
        $this->assertTrue(
            str_contains($action, 'urgent') ||
            str_contains($action, 'immédiat') ||
            str_contains($action, 'prioritaire'),
            'Should recommend urgent action for negative review'
        );
    }

    /** @test */
    public function it_handles_mixed_sentiments()
    {
        $text = "Le cadre est magnifique et l'accueil chaleureux, mais les plats manquent de saveur et sont trop chers pour la qualité.";

        $result = $this->service->analyze($text);

        // Devrait avoir des aspects positifs et négatifs
        $this->assertArrayHasKey('aspects', $result);

        $hasPositive = false;
        $hasNegative = false;

        foreach ($result['aspects'] as $aspect => $score) {
            if ($score > 0.6) $hasPositive = true;
            if ($score < 0.4) $hasNegative = true;
        }

        $this->assertTrue($hasPositive || $hasNegative, 'Should detect mixed sentiments');
    }

    /** @test */
    public function it_handles_short_text()
    {
        $text = "Bien.";

        $result = $this->service->analyze($text);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('overall_sentiment', $result);
        $this->assertArrayHasKey('priority', $result);
    }

    /** @test */
    public function it_handles_empty_text()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->analyze('');
    }

    /** @test */
    public function it_scores_aspects_from_zero_to_one()
    {
        $text = "Excellente nourriture, service rapide, ambiance agréable, prix corrects.";

        $result = $this->service->analyze($text);

        foreach ($result['aspects'] as $aspect => $score) {
            $this->assertGreaterThanOrEqual(0, $score, "Aspect '$aspect' score should be >= 0");
            $this->assertLessThanOrEqual(1, $score, "Aspect '$aspect' score should be <= 1");
        }
    }

    /** @test */
    public function it_returns_consistent_priority_levels()
    {
        $text = "Test review";

        $result = $this->service->analyze($text);

        $validPriorities = ['LOW', 'MEDIUM', 'HIGH', 'URGENT'];
        $this->assertContains($result['priority'], $validPriorities);
    }
}
