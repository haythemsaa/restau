<?php

namespace App\Services\AI;

use App\Models\Review;
use OpenAI\Laravel\Facades\OpenAI;

class ReviewResponseService
{
    /**
     * Generate automated response to a review
     */
    public function generateResponse(Review $review): string
    {
        $context = $this->buildContext($review);
        $prompt = $this->buildPrompt($review, $context);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es le responsable d\'un restaurant qui répond aux avis clients. Tes réponses sont professionnelles, empathiques et personnalisées. Tu t\'adresses directement au client.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 300,
        ]);

        return trim($response->choices[0]->message->content);
    }

    /**
     * Generate multiple response suggestions
     */
    public function suggestMultipleResponses(Review $review, int $count = 3): array
    {
        $responses = [];

        $tones = ['professional', 'warm', 'enthusiastic'];

        for ($i = 0; $i < min($count, count($tones)); $i++) {
            $responses[] = [
                'id' => $i + 1,
                'content' => $this->generateResponseWithTone($review, $tones[$i]),
                'tone' => $tones[$i],
                'length' => str_word_count($this->generateResponseWithTone($review, $tones[$i])),
            ];
        }

        return $responses;
    }

    /**
     * Generate response with specific tone
     */
    private function generateResponseWithTone(Review $review, string $tone): string
    {
        $context = $this->buildContext($review);
        $context['tone'] = $tone;

        $prompt = $this->buildPrompt($review, $context);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Tu es le responsable d'un restaurant qui répond aux avis clients avec un ton {$tone}."
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 300,
        ]);

        return trim($response->choices[0]->message->content);
    }

    /**
     * Auto-reply to a review
     */
    public function autoReply(Review $review, ?string $customMessage = null): void
    {
        if ($customMessage) {
            $reply = $customMessage;
        } else {
            $reply = $this->generateResponse($review);
        }

        $review->update([
            'reply' => $reply,
            'replied_at' => now(),
        ]);

        // Dispatch job to publish response to platform
        // dispatch(new SendReviewResponse($review));
    }

    /**
     * Build context for response generation
     */
    private function buildContext(Review $review): array
    {
        $business = $review->business;

        return [
            'business_name' => $business->name ?? 'notre restaurant',
            'business_type' => $business->type ?? 'restaurant',
            'rating' => $review->rating,
            'platform' => $review->platform,
            'sentiment_score' => $review->sentiment_score,
            'aspects' => $review->sentiment_aspects,
            'emotions' => $review->emotions,
            'has_specific_mentions' => $this->hasSpecificMentions($review->text),
        ];
    }

    /**
     * Build prompt for response generation
     */
    private function buildPrompt(Review $review, array $context): string
    {
        $toneGuideline = $this->getToneGuideline($review->rating);
        $responseStructure = $this->getResponseStructure($review->rating);

        $aspectsText = '';
        if (!empty($context['aspects'])) {
            $aspectsText = "\n**Aspects mentionnés:**";
            foreach ($context['aspects'] as $aspect => $data) {
                if (isset($data['score']) && $data['score'] !== null) {
                    $mentions = implode(', ', $data['mentions'] ?? []);
                    $aspectsText .= "\n- {$aspect}: {$mentions}";
                }
            }
        }

        return "Génère une réponse professionnelle à cet avis client:

**Restaurant:** {$context['business_name']}
**Plateforme:** {$context['platform']}
**Note:** {$review->rating}/5
**Avis du client:**
{$review->text}
{$aspectsText}

**Consignes:**
{$toneGuideline}
{$responseStructure}
- Utilise le prénom du client si mentionné: {$review->author_name}
- Maximum 150 mots
- Signe avec 'L'équipe de {$context['business_name']}' ou similaire
- Personnalise en référençant des éléments spécifiques de l'avis
- Reste authentique et sincère";
    }

    /**
     * Get tone guideline based on rating
     */
    private function getToneGuideline(float $rating): string
    {
        if ($rating >= 4.5) {
            return "- Ton: Enthousiaste et reconnaissant
- Exprime ta gratitude avec chaleur
- Encourage une future visite
- Mentionne des points spécifiques appréciés";
        }

        if ($rating >= 3.5) {
            return "- Ton: Professionnel et positif
- Remercie le client
- Mentionne l'amélioration continue
- Invite à revenir pour constater les progrès";
        }

        if ($rating >= 2) {
            return "- Ton: Empathique et constructif
- Présente des excuses sincères pour les points négatifs
- Explique comment tu vas améliorer
- Propose une solution concrète ou invitation à discuter";
        }

        return "- Ton: Très empathique et proactif
- Excuses immédiates et sincères
- Reconnaissance complète du problème
- Proposition de solution immédiate (remboursement, invitation, compensation)
- Invitation à discuter en privé (email/téléphone)";
    }

    /**
     * Get response structure based on rating
     */
    private function getResponseStructure(float $rating): string
    {
        if ($rating >= 4) {
            return "**Structure de la réponse:**
1. Remerciement chaleureux personnalisé
2. Référence à 2-3 points spécifiques mentionnés positivement
3. Invitation à revenir
4. Signature";
        }

        if ($rating >= 2.5) {
            return "**Structure de la réponse:**
1. Remerciement pour le retour
2. Reconnaissance des points positifs s'il y en a
3. Adresse les points d'amélioration avec transparence
4. Invitation à donner une nouvelle chance
5. Signature";
        }

        return "**Structure de la réponse:**
1. Excuses immédiates et sincères
2. Reconnaissance spécifique de chaque problème mentionné
3. Explication de ce qui a mal fonctionné (sans excuses)
4. Actions concrètes mises en place
5. Proposition de compensation ou invitation à discuter
6. Contact direct (email/téléphone)
7. Signature";
    }

    /**
     * Check if review has specific mentions
     */
    private function hasSpecificMentions(string $text): bool
    {
        // Check if review mentions specific items, people, or experiences
        $specificIndicators = [
            'plat', 'menu', 'serveur', 'serveuse', 'chef', 'entrée', 'dessert',
            'vin', 'boisson', 'table', 'salle', 'terrasse', 'réservation'
        ];

        $textLower = strtolower($text);

        foreach ($specificIndicators as $indicator) {
            if (str_contains($textLower, $indicator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate response before sending
     */
    public function validateResponse(string $response): array
    {
        $issues = [];

        // Check length
        $wordCount = str_word_count($response);
        if ($wordCount < 20) {
            $issues[] = 'Response too short (minimum 20 words)';
        }
        if ($wordCount > 200) {
            $issues[] = 'Response too long (maximum 200 words)';
        }

        // Check for generic responses
        $genericPhrases = [
            'merci pour votre avis',
            'nous sommes ravis',
            'à bientôt',
        ];

        $genericCount = 0;
        $responseLower = strtolower($response);

        foreach ($genericPhrases as $phrase) {
            if (str_contains($responseLower, $phrase)) {
                $genericCount++;
            }
        }

        if ($genericCount >= 3) {
            $issues[] = 'Response seems too generic, add more personalization';
        }

        // Check for signature
        if (!preg_match('/(équipe|cordialement|bien à vous)/i', $response)) {
            $issues[] = 'Missing proper signature';
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'word_count' => $wordCount,
            'has_personalization' => $genericCount < 2,
        ];
    }

    /**
     * Generate response template for manual editing
     */
    public function generateTemplate(Review $review): array
    {
        $response = $this->generateResponse($review);

        return [
            'template' => $response,
            'editable_sections' => [
                'greeting' => $this->extractGreeting($response),
                'body' => $this->extractBody($response),
                'signature' => $this->extractSignature($response),
            ],
            'suggestions' => [
                'add_promo_code' => $review->rating >= 4,
                'add_contact_info' => $review->rating < 3,
                'add_specific_mention' => $this->hasSpecificMentions($review->text),
            ],
        ];
    }

    /**
     * Extract greeting from response
     */
    private function extractGreeting(string $response): string
    {
        $lines = explode("\n", $response);
        return $lines[0] ?? '';
    }

    /**
     * Extract body from response
     */
    private function extractBody(string $response): string
    {
        $lines = explode("\n", $response);
        $bodyLines = array_slice($lines, 1, -1);
        return implode("\n", $bodyLines);
    }

    /**
     * Extract signature from response
     */
    private function extractSignature(string $response): string
    {
        $lines = explode("\n", $response);
        return end($lines);
    }
}
