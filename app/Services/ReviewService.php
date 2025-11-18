<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\Http;

class ReviewService
{
    /**
     * Analyze sentiment of review text using AI.
     */
    public function analyzeSentiment(string $text): float
    {
        // Placeholder for AI sentiment analysis
        // In production, integrate with OpenAI, AWS Comprehend, or similar
        $positiveWords = ['excellent', 'parfait', 'délicieux', 'superbe', 'génial', 'recommande'];
        $negativeWords = ['mauvais', 'déçu', 'attente', 'froid', 'sale'];

        $score = 0.5; // Neutral
        $text = strtolower($text);

        foreach ($positiveWords as $word) {
            if (str_contains($text, $word)) {
                $score += 0.1;
            }
        }

        foreach ($negativeWords as $word) {
            if (str_contains($text, $word)) {
                $score -= 0.1;
            }
        }

        return max(-1, min(1, $score));
    }

    /**
     * Extract categories from review text.
     */
    public function extractCategories(string $text): array
    {
        $categories = [];
        $text = strtolower($text);

        $mapping = [
            'nourriture' => ['nourriture', 'plat', 'cuisine', 'repas', 'menu'],
            'service' => ['service', 'serveur', 'accueil', 'personnel'],
            'ambiance' => ['ambiance', 'cadre', 'décor', 'atmosphère'],
            'prix' => ['prix', 'cher', 'rapport qualité'],
        ];

        foreach ($mapping as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $sentiment = $this->analyzeSentiment($text) > 0.3 ? '+' : '-';
                    $categories[] = ucfirst($category) . $sentiment;
                    break;
                }
            }
        }

        return array_unique($categories);
    }

    /**
     * Generate automatic response to review.
     */
    public function generateResponse(Review $review): string
    {
        $business = $review->business;
        $rating = $review->rating;

        if ($rating >= 4.5) {
            return "Merci {$review->author_name} pour votre excellent retour ! Nous sommes ravis que vous ayez apprécié votre expérience chez {$business->name}. Au plaisir de vous revoir bientôt !";
        } elseif ($rating >= 3.5) {
            return "Merci {$review->author_name} pour votre avis. Nous sommes heureux que vous ayez apprécié votre visite. N'hésitez pas à revenir nous voir !";
        } else {
            return "Bonjour {$review->author_name}, nous sommes désolés que votre expérience n'ait pas été à la hauteur de vos attentes. Votre retour est précieux et nous permet de nous améliorer. Nous espérons avoir l'opportunité de vous accueillir à nouveau dans de meilleures conditions.";
        }
    }

    /**
     * Calculate average rating for a business.
     */
    public function calculateAverageRating(int $businessId): float
    {
        return Review::where('business_id', $businessId)
            ->avg('rating') ?? 0;
    }
}
