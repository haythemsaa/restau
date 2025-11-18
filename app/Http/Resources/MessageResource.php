<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'conversation_id' => $this->conversation_id,
            'direction' => $this->direction,
            'sender_type' => $this->sender_type,
            'sender_id' => $this->sender_id,
            'content' => $this->content,
            'timestamp' => $this->timestamp?->toISOString(),
            'read_at' => $this->read_at?->toISOString(),
        ];
    }
}
