<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;

class ContentGeneratorService
{
    /**
     * Generate social media content using AI
     */
    public function generateSocialPost(array $params): array
    {
        $prompt = $this->buildPrompt($params);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un expert en marketing pour restaurants. Tu crées du contenu engageant, authentique et optimisé pour les réseaux sociaux. Tu utilises un ton chaleureux et professionnel.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        $content = $response->choices[0]->message->content;

        return [
            'content' => $content,
            'hashtags' => $this->extractHashtags($content),
            'word_count' => str_word_count($content),
            'best_time' => $this->getBestPostingTime($params['platform']),
        ];
    }

    /**
     * Generate multiple content variations
     */
    public function generateVariations(array $params, int $count = 3): array
    {
        $variations = [];

        for ($i = 0; $i < $count; $i++) {
            $variations[] = $this->generateSocialPost($params);
        }

        return $variations;
    }

    /**
     * Generate hashtags suggestions
     */
    public function suggestHashtags(string $content, string $industry = 'restaurant'): array
    {
        $prompt = "Suggère 10 hashtags pertinents et populaires pour cette publication de restaurant:

{$content}

Retourne uniquement les hashtags séparés par des virgules, sans numérotation.";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.5,
            'max_tokens' => 150,
        ]);

        $hashtags = $response->choices[0]->message->content;
        return array_map('trim', explode(',', $hashtags));
    }

    /**
     * Generate image description/alt text
     */
    public function generateImageDescription(string $context): string
    {
        $prompt = "Génère une description ALT courte et descriptive pour une image de restaurant dans ce contexte: {$context}. Maximum 125 caractères.";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 50,
        ]);

        return trim($response->choices[0]->message->content);
    }

    /**
     * Build prompt for content generation
     */
    private function buildPrompt(array $params): string
    {
        $platform = $params['platform'] ?? 'instagram';
        $theme = $params['theme'] ?? 'promotion';
        $tone = $params['tone'] ?? 'friendly';
        $audience = $params['audience'] ?? 'food lovers';
        $businessName = $params['business_name'] ?? 'notre restaurant';
        $specificDetails = $params['details'] ?? '';

        $platformGuidelines = $this->getPlatformGuidelines($platform);

        return "Crée une publication engageante pour {$platform} pour {$businessName}.

**Thème:** {$theme}
**Ton:** {$tone}
**Public cible:** {$audience}
**Détails supplémentaires:** {$specificDetails}

**Guidelines {$platform}:**
{$platformGuidelines}

Inclus des emojis pertinents et 3-5 hashtags pertinents à la fin.
La publication doit être authentique, engageante et inciter à l'action.";
    }

    /**
     * Get platform-specific guidelines
     */
    private function getPlatformGuidelines(string $platform): string
    {
        $guidelines = [
            'instagram' => '- Longueur optimale: 125-150 mots
- Utilise des emojis pour structurer le contenu
- Première phrase accrocheuse
- Call-to-action clair
- Hashtags à la fin',

            'facebook' => '- Longueur: 80-100 mots
- Ton conversationnel
- Pose une question pour engager
- Liens acceptés
- Moins d\'emojis qu\'Instagram',

            'twitter' => '- Maximum 280 caractères
- Message concis et percutant
- 1-2 hashtags maximum
- Appel à l\'action direct',

            'linkedin' => '- Longueur: 150-200 mots
- Ton professionnel mais accessible
- Raconte une histoire
- Focus sur les valeurs et l\'expertise
- Hashtags professionnels',
        ];

        return $guidelines[$platform] ?? $guidelines['instagram'];
    }

    /**
     * Extract hashtags from content
     */
    private function extractHashtags(string $content): array
    {
        preg_match_all('/#(\w+)/', $content, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Get best posting time for platform
     */
    private function getBestPostingTime(string $platform): array
    {
        $times = [
            'instagram' => [
                'best_days' => ['Mardi', 'Mercredi', 'Jeudi'],
                'best_hours' => ['11:00-13:00', '19:00-21:00'],
                'worst_day' => 'Dimanche',
            ],
            'facebook' => [
                'best_days' => ['Mercredi', 'Jeudi', 'Vendredi'],
                'best_hours' => ['12:00-15:00', '18:00-20:00'],
                'worst_day' => 'Samedi',
            ],
            'twitter' => [
                'best_days' => ['Lundi', 'Mercredi', 'Vendredi'],
                'best_hours' => ['08:00-10:00', '12:00-13:00'],
                'worst_day' => 'Dimanche',
            ],
            'linkedin' => [
                'best_days' => ['Mardi', 'Mercredi', 'Jeudi'],
                'best_hours' => ['07:00-09:00', '17:00-18:00'],
                'worst_day' => 'Weekend',
            ],
        ];

        return $times[$platform] ?? $times['instagram'];
    }

    /**
     * Optimize content for SEO
     */
    public function optimizeForSEO(string $content, array $keywords): string
    {
        $prompt = "Optimise ce contenu pour le SEO en incluant naturellement ces mots-clés: " . implode(', ', $keywords) . "

Contenu original:
{$content}

Retourne le contenu optimisé sans changer le ton ni le message principal.";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
        ]);

        return $response->choices[0]->message->content;
    }
}
