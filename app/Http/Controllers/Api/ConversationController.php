<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $conversations = Conversation::query()
            ->with(['business', 'assignedUser'])
            ->withCount('messages')
            ->when($request->business_id, fn($q) => $q->where('business_id', $request->business_id))
            ->when($request->channel, fn($q) => $q->where('channel', $request->channel))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->assigned_to, fn($q) => $q->where('assigned_to', $request->assigned_to))
            ->orderBy('updated_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created conversation.
     */
    public function store(StoreConversationRequest $request): JsonResponse
    {
        $conversation = Conversation::create($request->validated());

        return response()->json([
            'message' => 'Conversation created successfully',
            'data' => new ConversationResource($conversation->load(['business', 'assignedUser'])),
        ], 201);
    }

    /**
     * Display the specified conversation.
     */
    public function show(Conversation $conversation): ConversationResource
    {
        return new ConversationResource(
            $conversation->load(['business', 'assignedUser', 'messages'])
                ->loadCount('messages')
        );
    }

    /**
     * Update the specified conversation.
     */
    public function update(Request $request, Conversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'assigned_to' => ['nullable', 'uuid', 'exists:users,id'],
            'status' => ['sometimes', 'in:new,open,pending,resolved,closed'],
            'tags' => ['nullable', 'array'],
        ]);

        if (isset($validated['status']) && $validated['status'] === 'resolved') {
            $validated['resolved_at'] = now();
        }

        $conversation->update($validated);

        return response()->json([
            'message' => 'Conversation updated successfully',
            'data' => new ConversationResource($conversation->fresh()->load(['business', 'assignedUser'])),
        ]);
    }

    /**
     * Remove the specified conversation.
     */
    public function destroy(Conversation $conversation): JsonResponse
    {
        $conversation->delete();

        return response()->json([
            'message' => 'Conversation deleted successfully',
        ]);
    }

    /**
     * Store a new message in the conversation.
     */
    public function storeMessage(StoreMessageRequest $request, Conversation $conversation): JsonResponse
    {
        $data = $request->validated();
        $data['timestamp'] = now();

        $message = $conversation->messages()->create($data);

        // Update conversation status
        if ($conversation->status === 'new') {
            $conversation->update(['status' => 'open']);
        }

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => new MessageResource($message),
        ], 201);
    }

    /**
     * Get messages for a conversation.
     */
    public function messages(Conversation $conversation): AnonymousResourceCollection
    {
        $messages = $conversation->messages()
            ->orderBy('timestamp', 'asc')
            ->get();

        return MessageResource::collection($messages);
    }
}
