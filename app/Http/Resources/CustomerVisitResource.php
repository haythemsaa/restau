<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerVisitResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'business_id' => $this->business_id,
            'visited_at' => $this->visited_at?->toISOString(),
            'visited_date' => $this->visited_at?->format('Y-m-d'),
            'visited_time' => $this->visited_at?->format('H:i'),
            'amount_spent' => number_format($this->amount_spent, 2),
            'party_size' => $this->party_size,
            'items_ordered' => $this->items_ordered,
            'satisfaction_score' => $this->satisfaction_score,
            'source' => $this->source,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Include customer info if loaded
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'full_name' => $this->customer->full_name,
                    'email' => $this->customer->email,
                    'tier' => $this->customer->tier,
                ];
            }),

            // Include business info if loaded
            'business' => $this->whenLoaded('business', function () {
                return [
                    'id' => $this->business->id,
                    'name' => $this->business->name,
                ];
            }),
        ];
    }
}
