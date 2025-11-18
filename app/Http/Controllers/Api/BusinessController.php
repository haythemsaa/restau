<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BusinessController extends Controller
{
    /**
     * Display a listing of businesses.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $businesses = Business::query()
            ->with(['group'])
            ->withCount(['users', 'reviews'])
            ->when($request->group_id, fn($q) => $q->where('group_id', $request->group_id))
            ->paginate($request->per_page ?? 15);

        return BusinessResource::collection($businesses);
    }

    /**
     * Store a newly created business.
     */
    public function store(StoreBusinessRequest $request): JsonResponse
    {
        $business = Business::create($request->validated());

        return response()->json([
            'message' => 'Business created successfully',
            'data' => new BusinessResource($business->load('group')),
        ], 201);
    }

    /**
     * Display the specified business.
     */
    public function show(Business $business): BusinessResource
    {
        return new BusinessResource(
            $business->load(['group', 'users', 'reviews'])
                ->loadCount(['users', 'reviews', 'socialPosts', 'conversations'])
        );
    }

    /**
     * Update the specified business.
     */
    public function update(UpdateBusinessRequest $request, Business $business): JsonResponse
    {
        $business->update($request->validated());

        return response()->json([
            'message' => 'Business updated successfully',
            'data' => new BusinessResource($business->fresh()->load('group')),
        ]);
    }

    /**
     * Remove the specified business.
     */
    public function destroy(Business $business): JsonResponse
    {
        $business->delete();

        return response()->json([
            'message' => 'Business deleted successfully',
        ]);
    }
}
