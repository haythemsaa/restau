<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
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
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'metadata' => $this->metadata,
            'group' => $this->whenLoaded('group', function () {
                return [
                    'id' => $this->group->id,
                    'name' => $this->group->name,
                ];
            }),
            'users_count' => $this->whenCounted('users'),
            'reviews_count' => $this->whenCounted('reviews'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
