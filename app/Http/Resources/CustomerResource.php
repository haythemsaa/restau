<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->birth_date ? now()->diffInYears($this->birth_date) : null,
            'preferences' => $this->preferences,
            'tags' => $this->tags,
            'tier' => $this->tier,
            'is_vip' => $this->is_vip,
            'is_super_vip' => $this->is_super_vip,
            'lifetime_value' => number_format($this->lifetime_value, 2),
            'visit_count' => $this->visit_count,
            'average_spend' => $this->average_spend,
            'last_visit_at' => $this->last_visit_at?->toISOString(),
            'days_since_last_visit' => $this->days_since_last_visit,
            'language' => $this->language,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Include RFM score if requested
            'rfm_score' => $this->when($request->boolean('include_rfm'), function () {
                return $this->getRFMScore();
            }),

            // Include relationships if loaded
            'segments' => $this->whenLoaded('segments', function () {
                return $this->segments->map(function ($segment) {
                    return [
                        'id' => $segment->id,
                        'name' => $segment->name,
                        'color' => $segment->color,
                    ];
                });
            }),

            'latest_visits' => $this->whenLoaded('visits', function () use ($request) {
                return CustomerVisitResource::collection(
                    $this->visits->take($request->input('visits_limit', 5))
                );
            }),

            'visits_count' => $this->when($this->relationLoaded('visits'), function () {
                return $this->visits->count();
            }),
        ];
    }
}
