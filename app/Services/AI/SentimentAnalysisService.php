<?php

namespace App\Services\AI;

use App\Models\Review;
use OpenAI\Laravel\Facades\OpenAI;

class SentimentAnalysisService
{
    /**
     * Analyze sentiment of text
     */
    public function analyze(string $text): array
    {
        // Get overall sentiment
        $sentiment = $this->analyzeSentiment($text);

        // Extract aspects (food, service, ambiance, price)
        $aspects = $this->extractAspects($text);

        // Detect emotions
        $emotions = $this->detectEmotions($text);

        // Generate actionable insights
        $insights = $this->generateInsights($sentiment, $aspects, $emotions);

        // Calculate priority
        $priority = $this->calculatePriority($sentiment, $emotions);

        return [
            'overall_sentiment' => $sentiment,
            'aspects' => $aspects,
            'emotions' => $emotions,
            'insights' => $insights,
            'priority' => $priority,
            'recommended_action' => $this->getRecommendedAction($priority, $sentiment),
        ];
    }

    /**
     * Analyze review and update database
     */
    public function analyzeReview(Review $review): void
    {
        $analysis = $this->analyze($review->text ?? '');

        $review->update([
            'sentiment_score' => $analysis['overall_sentiment']['score'],
            'sentiment_aspects' => $analysis['aspects'],
            'emotions' => $analysis['emotions'],
            'priority' => $analysis['priority'],
            'ai_insights' => implode("\n", $analysis['insights']),
        ]);

        // Create alert if urgent
        if ($analysis['priority'] === 'URGENT') {
            $this->createUrgentAlert($review);
        }
    }

    /**
     * Analyze overall sentiment using AI
     */
    private function analyzeSentiment(string $text): array
    {
        $prompt = "Analyse le sentiment de cet avis client de restaurant et retourne un score de -1 (très négatif) à +1 (très positif).

Avis: {$text}

Retourne uniquement le score numérique avec 2 décimales, suivi d'un espace et du label (Très Positif/Positif/Neutre/Négatif/Très Négatif).
Format: 0.85 Très Positif";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3,
            'max_tokens' => 50,
        ]);

        $result = $response->choices[0]->message->content;
        preg_match('/([-\d.]+)\s+(.+)/', $result, $matches);

        return [
            'score' => (float) ($matches[1] ?? 0),
            'label' => $matches[2] ?? 'Neutre',
        ];
    }

    /**
     * Extract aspects (food, service, ambiance, price)
     */
    private function extractAspects(string $text): array
    {
        $prompt = "Analyse cet avis de restaurant et identifie le sentiment pour chaque aspect.

Avis: {$text}

Pour chaque aspect (Nourriture, Service, Ambiance, Prix), donne un score de -1 à +1 et liste les mentions clés.

Format JSON:
{
  \"food\": {\"score\": 0.8, \"mentions\": [\"délicieux\", \"frais\"]},
  \"service\": {\"score\": 0.5, \"mentions\": [\"correct\"]},
  \"ambiance\": {\"score\": 0.9, \"mentions\": [\"cosy\", \"chaleureux\"]},
  \"price\": {\"score\": -0.3, \"mentions\": [\"un peu cher\"]}
}

Si un aspect n'est pas mentionné, utilise null pour le score.";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3,
            'max_tokens' => 300,
        ]);

        $result = $response->choices[0]->message->content;

        // Extract JSON from response
        preg_match('/\{[\s\S]*\}/', $result, $matches);

        if (isset($matches[0])) {
            return json_decode($matches[0], true) ?? $this->getDefaultAspects();
        }

        return $this->getDefaultAspects();
    }

    /**
     * Detect emotions in text
     */
    private function detectEmotions(string $text): array
    {
        $emotions = [];

        $emotionKeywords = [
            'joy' => ['content', 'heureux', 'ravi', 'excellent', 'génial', 'parfait', 'merveilleux', 'superbe'],
            'disappointment' => ['déçu', 'dommage', 'malheureusement', 'décevant', 'regret'],
            'anger' => ['inacceptable', 'scandaleux', 'inadmissible', 'honteux', 'furieux', 'mécontent'],
            'surprise' => ['surpris', 'étonnant', 'inattendu', 'surprise', 'wow'],
            'satisfaction' => ['satisfait', 'bon rapport', 'correct', 'convenable', 'acceptable'],
        ];

        $textLower = strtolower($text);

        foreach ($emotionKeywords as $emotion => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($textLower, $keyword)) {
                    $emotions[] = $emotion;
                    break;
                }
            }
        }

        return array_unique($emotions);
    }

    /**
     * Generate actionable insights
     */
    private function generateInsights(array $sentiment, array $aspects, array $emotions): array
    {
        $insights = [];

        // Analyze negative aspects
        foreach ($aspects as $aspect => $data) {
            if (isset($data['score']) && $data['score'] !== null && $data['score'] < -0.3) {
                $mentions = implode(', ', $data['mentions'] ?? []);
                $insights[] = "⚠️ Point d'amélioration: {$aspect} (mentions: {$mentions})";
            }
        }

        // Analyze positive aspects
        foreach ($aspects as $aspect => $data) {
            if (isset($data['score']) && $data['score'] !== null && $data['score'] > 0.7) {
                $mentions = implode(', ', $data['mentions'] ?? []);
                $insights[] = "✅ Point fort: {$aspect} (mentions: {$mentions})";
            }
        }

        // Check for at-risk customers
        if (in_array('anger', $emotions) || in_array('disappointment', $emotions)) {
            $insights[] = "🚨 Client mécontent - Action urgente requise pour éviter impact réputation";
        }

        // Positive feedback
        if (in_array('joy', $emotions) && $sentiment['score'] > 0.6) {
            $insights[] = "🌟 Client très satisfait - Opportunité pour témoignage ou partage";
        }

        return $insights;
    }

    /**
     * Calculate priority level
     */
    private function calculatePriority(array $sentiment, array $emotions): string
    {
        if (in_array('anger', $emotions)) {
            return 'URGENT';
        }

        if ($sentiment['score'] <= -0.5) {
            return 'HIGH';
        }

        if ($sentiment['score'] <= 0) {
            return 'MEDIUM';
        }

        return 'LOW';
    }

    /**
     * Get recommended action based on priority
     */
    private function getRecommendedAction(string $priority, array $sentiment): string
    {
        $actions = [
            'URGENT' => 'Répondre immédiatement (dans l\'heure). Contacter le client par téléphone si possible. Proposer une compensation.',
            'HIGH' => 'Répondre dans les 24h. Présenter des excuses sincères et proposer une solution concrète.',
            'MEDIUM' => 'Répondre dans les 48h. Remercier et mentionner les améliorations en cours.',
            'LOW' => 'Répondre dans la semaine. Remercier chaleureusement le client pour son retour positif.',
        ];

        return $actions[$priority] ?? $actions['MEDIUM'];
    }

    /**
     * Create urgent alert for negative review
     */
    private function createUrgentAlert(Review $review): void
    {
        // This would send notifications to restaurant managers
        // Implementation depends on notification system
        logger()->critical('Urgent review requires attention', [
            'review_id' => $review->id,
            'business_id' => $review->business_id,
            'rating' => $review->rating,
            'platform' => $review->platform,
        ]);

        // Could dispatch a notification job here
        // dispatch(new SendUrgentReviewAlert($review));
    }

    /**
     * Get default aspects structure
     */
    private function getDefaultAspects(): array
    {
        return [
            'food' => ['score' => null, 'mentions' => []],
            'service' => ['score' => null, 'mentions' => []],
            'ambiance' => ['score' => null, 'mentions' => []],
            'price' => ['score' => null, 'mentions' => []],
        ];
    }

    /**
     * Batch analyze multiple reviews
     */
    public function batchAnalyze(array $reviews): array
    {
        $results = [];

        foreach ($reviews as $review) {
            if ($review instanceof Review) {
                $this->analyzeReview($review);
                $results[] = [
                    'review_id' => $review->id,
                    'status' => 'analyzed',
                    'priority' => $review->priority,
                ];
            }
        }

        return $results;
    }
}
