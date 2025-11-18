<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_id' => $this->business_id,
            'channel' => $this->channel,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'tags' => $this->tags,
            'resolved_at' => $this->resolved_at?->toISOString(),
            'business' => new BusinessResource($this->whenLoaded('business')),
            'assigned_user' => new UserResource($this->whenLoaded('assignedUser')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'messages_count' => $this->whenCounted('messages'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
