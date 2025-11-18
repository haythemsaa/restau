<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialPostResource extends JsonResource
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
            'platforms' => $this->platforms,
            'content' => $this->content,
            'media_urls' => $this->media_urls,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'published_at' => $this->published_at?->toISOString(),
            'status' => $this->status,
            'metrics' => $this->metrics,
            'business' => new BusinessResource($this->whenLoaded('business')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
