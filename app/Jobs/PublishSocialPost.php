<?php

namespace App\Jobs;

use App\Models\SocialPost;
use App\Services\SocialPostService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishSocialPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SocialPost $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(SocialPostService $service): void
    {
        $this->post->update(['status' => 'publishing']);

        try {
            $results = $service->publishToPlatforms($this->post);

            $this->post->update([
                'status' => 'published',
                'published_at' => now(),
                'metrics' => $results,
            ]);
        } catch (\Exception $e) {
            $this->post->update(['status' => 'failed']);
            throw $e;
        }
    }
}
