<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'platform' => $this->platform,
            'platform_review_id' => $this->platform_review_id,
            'author_name' => $this->author_name,
            'rating' => (float) $this->rating,
            'text' => $this->text,
            'response_text' => $this->response_text,
            'response_date' => $this->response_date?->toISOString(),
            'sentiment_score' => $this->sentiment_score ? (float) $this->sentiment_score : null,
            'categories' => $this->categories,
            'business' => new BusinessResource($this->whenLoaded('business')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
