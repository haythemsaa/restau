<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocialPostRequest;
use App\Http\Requests\UpdateSocialPostRequest;
use App\Http\Resources\SocialPostResource;
use App\Models\SocialPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialPostController extends Controller
{
    /**
     * Display a listing of social posts.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = SocialPost::query()
            ->with(['business', 'creator'])
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->platform, fn($q) => $q->whereJsonContains('platforms', $request->platform))
            ->orderBy('scheduled_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return SocialPostResource::collection($posts);
    }

    /**
     * Store a newly created social post.
     */
    public function store(StoreSocialPostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'draft';

        $post = SocialPost::create($data);

        return response()->json([
            'message' => 'Social post created successfully',
            'data' => new SocialPostResource($post->load(['business', 'creator'])),
        ], 201);
    }

    /**
     * Display the specified social post.
     */
    public function show(SocialPost $socialPost): SocialPostResource
    {
        return new SocialPostResource($socialPost->load(['business', 'creator']));
    }

    /**
     * Update the specified social post.
     */
    public function update(UpdateSocialPostRequest $request, SocialPost $socialPost): JsonResponse
    {
        $socialPost->update($request->validated());

        return response()->json([
            'message' => 'Social post updated successfully',
            'data' => new SocialPostResource($socialPost->fresh()->load(['business', 'creator'])),
        ]);
    }

    /**
     * Remove the specified social post.
     */
    public function destroy(SocialPost $socialPost): JsonResponse
    {
        $socialPost->delete();

        return response()->json([
            'message' => 'Social post deleted successfully',
        ]);
    }

    /**
     * Publish a scheduled post immediately.
     */
    public function publish(SocialPost $socialPost): JsonResponse
    {
        $socialPost->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Social post published successfully',
            'data' => new SocialPostResource($socialPost->fresh()->load(['business', 'creator'])),
        ]);
    }
}
