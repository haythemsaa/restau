<?php

namespace App\Jobs;

use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessReviewSentiment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Review $review
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ReviewService $reviewService): void
    {
        $sentiment = $reviewService->analyzeSentiment($this->review->text);
        $categories = $reviewService->extractCategories($this->review->text);

        $this->review->update([
            'sentiment_score' => $sentiment,
            'categories' => $categories,
        ]);
    }
}
