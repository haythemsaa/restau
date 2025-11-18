<?php

namespace App\Jobs;

use App\Models\CampaignSend;
use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CampaignSend $send
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $campaign = $this->send->campaign;
            $customer = $this->send->customer;

            // Prepare variables for template
            $variables = array_merge($campaign->variables ?? [], [
                'customer_first_name' => $customer->first_name,
                'customer_full_name' => $customer->full_name,
                'business_name' => $campaign->business->name,
            ]);

            // Render content with variables
            $content = $this->renderTemplate($campaign->content, $variables);
            $subject = $this->renderTemplate($campaign->subject, $variables);

            // Generate tracking pixel if enabled
            if ($campaign->track_opens) {
                $trackingUrl = route('email.track-open', ['send' => $this->send->id]);
                $trackingPixel = "<img src=\"{$trackingUrl}\" alt=\"\" width=\"1\" height=\"1\" />";
                $content .= $trackingPixel;
            }

            // Send email
            Mail::send([], [], function ($message) use ($campaign, $subject, $content) {
                $message->to($this->send->recipient_email, $this->send->recipient_name)
                        ->subject($subject)
                        ->from($campaign->from_email ?? config('mail.from.address'),
                               $campaign->from_name ?? config('mail.from.name'))
                        ->html($content);

                if ($campaign->reply_to) {
                    $message->replyTo($campaign->reply_to);
                }
            });

            // Mark as sent
            $this->send->markAsSent();

            Log::info("Campaign email sent successfully", [
                'campaign_id' => $campaign->id,
                'send_id' => $this->send->id,
                'recipient' => $this->send->recipient_email,
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send campaign email", [
                'campaign_id' => $this->send->campaign_id,
                'send_id' => $this->send->id,
                'error' => $e->getMessage(),
            ]);

            $this->send->markAsFailed($e->getMessage());

            // Re-throw to trigger retry
            if ($this->attempts() < $this->tries) {
                throw $e;
            }
        }
    }

    /**
     * Render template with variables
     */
    private function renderTemplate(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace("{{" . $key . "}}", $value, $template);
        }

        return $template;
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Campaign email job failed permanently", [
            'send_id' => $this->send->id,
            'error' => $exception->getMessage(),
        ]);

        $this->send->markAsFailed($exception->getMessage());
    }
}
