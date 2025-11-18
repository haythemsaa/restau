<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $reviews = Review::query()
            ->with(['business'])
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->platform, fn($q) => $q->where('platform', $request->platform))
            ->when($request->min_rating, fn($q) => $q->where('rating', '>=', $request->min_rating))
            ->when($request->has_response, fn($q) => $q->whereNotNull('response_text'))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json([
            'message' => 'Review created successfully',
            'data' => new ReviewResource($review->load('business')),
        ], 201);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review->load('business'));
    }

    /**
     * Update the specified review (mainly for adding responses).
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        $validated = $request->validate([
            'response_text' => ['nullable', 'string'],
            'sentiment_score' => ['nullable', 'numeric', 'min:-1', 'max:1'],
            'categories' => ['nullable', 'array'],
        ]);

        if (isset($validated['response_text'])) {
            $validated['response_date'] = now();
        }

        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'data' => new ReviewResource($review->fresh()->load('business')),
        ]);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
