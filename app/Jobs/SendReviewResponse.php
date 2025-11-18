<?php

namespace App\Jobs;

use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReviewResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Review $review,
        public ?string $customResponse = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ReviewService $service): void
    {
        $response = $this->customResponse ?? $service->generateResponse($this->review);

        $this->review->update([
            'response_text' => $response,
            'response_date' => now(),
        ]);

        // In production: Actually post response to review platform API
    }
}
