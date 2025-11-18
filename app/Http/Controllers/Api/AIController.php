<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\AI\ContentGeneratorService;
use App\Services\AI\ReviewResponseService;
use App\Services\AI\SentimentAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    public function __construct(
        private ContentGeneratorService $contentGenerator,
        private SentimentAnalysisService $sentimentAnalyzer,
        private ReviewResponseService $reviewResponder
    ) {}

    /**
     * Generate social media content
     */
    public function generateContent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'platform' => 'required|in:facebook,instagram,twitter,linkedin',
            'theme' => 'required|string|max:500',
            'tone' => 'required|in:professional,friendly,casual,enthusiastic',
            'audience' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        try {
            $result = $this->contentGenerator->generateSocialPost($validated);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate multiple content variations
     */
    public function generateVariations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'platform' => 'required|in:facebook,instagram,twitter,linkedin',
            'theme' => 'required|string',
            'tone' => 'required|in:professional,friendly,casual,enthusiastic',
            'count' => 'integer|min:1|max:5',
        ]);

        $count = $validated['count'] ?? 3;

        try {
            $variations = $this->contentGenerator->generateVariations($validated, $count);

            return response()->json([
                'success' => true,
                'data' => $variations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate variations',
            ], 500);
        }
    }

    /**
     * Analyze sentiment of review
     */
    public function analyzeSentiment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        try {
            $analysis = $this->sentimentAnalyzer->analyze($validated['text']);

            return response()->json([
                'success' => true,
                'data' => $analysis,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze sentiment',
            ], 500);
        }
    }

    /**
     * Analyze review by ID
     */
    public function analyzeReview(Review $review): JsonResponse
    {
        try {
            $this->sentimentAnalyzer->analyzeReview($review);

            return response()->json([
                'success' => true,
                'data' => [
                    'review_id' => $review->id,
                    'sentiment_score' => $review->sentiment_score,
                    'aspects' => $review->sentiment_aspects,
                    'emotions' => $review->emotions,
                    'priority' => $review->priority,
                    'insights' => $review->ai_insights,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze review',
            ], 500);
        }
    }

    /**
     * Generate response for review
     */
    public function generateReviewResponse(Review $review): JsonResponse
    {
        try {
            $response = $this->reviewResponder->generateResponse($review);

            return response()->json([
                'success' => true,
                'data' => [
                    'response' => $response,
                    'word_count' => str_word_count($response),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate response',
            ], 500);
        }
    }

    /**
     * Generate multiple response suggestions
     */
    public function suggestReviewResponses(Review $review): JsonResponse
    {
        try {
            $suggestions = $this->reviewResponder->suggestMultipleResponses($review, 3);

            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => $suggestions,
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'text' => $review->text,
                        'author' => $review->author_name,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate suggestions',
            ], 500);
        }
    }

    /**
     * Auto-reply to review
     */
    public function autoReply(Request $request, Review $review): JsonResponse
    {
        $validated = $request->validate([
            'custom_message' => 'nullable|string',
        ]);

        try {
            $this->reviewResponder->autoReply($review, $validated['custom_message'] ?? null);

            return response()->json([
                'success' => true,
                'message' => 'Response posted successfully',
                'data' => [
                    'review_id' => $review->id,
                    'reply' => $review->reply,
                    'replied_at' => $review->replied_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to post response',
            ], 500);
        }
    }

    /**
     * Suggest hashtags
     */
    public function suggestHashtags(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'industry' => 'nullable|string',
        ]);

        try {
            $hashtags = $this->contentGenerator->suggestHashtags(
                $validated['content'],
                $validated['industry'] ?? 'restaurant'
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'hashtags' => $hashtags,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to suggest hashtags',
            ], 500);
        }
    }
}
