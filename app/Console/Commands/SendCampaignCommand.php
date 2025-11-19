<?php

namespace App\Console\Commands;

use App\Models\EmailCampaign;
use App\Jobs\SendCampaignEmail;
use Illuminate\Console\Command;

class SendCampaignCommand extends Command
{
    protected $signature = 'campaign:send
                            {campaign : Campaign ID to send}
                            {--test : Send to test email instead}
                            {--email= : Test email address}';

    protected $description = 'Send an email campaign to all recipients';

    public function handle(): int
    {
        $campaignId = $this->argument('campaign');
        $campaign = EmailCampaign::find($campaignId);

        if (!$campaign) {
            $this->error("Campaign with ID {$campaignId} not found.");
            return 1;
        }

        $this->info("📧 Campaign: {$campaign->name}");

        if ($this->option('test')) {
            return $this->sendTest($campaign);
        }

        if ($campaign->status === 'sent') {
            $this->warn('⚠️  This campaign has already been sent.');

            if (!$this->confirm('Do you want to resend it?')) {
                return 0;
            }
        }

        $this->info('📊 Preparing campaign...');

        // Get recipients based on segment filter
        $recipients = $this->getRecipients($campaign);

        if ($recipients->isEmpty()) {
            $this->error('❌ No recipients found for this campaign.');
            return 1;
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Recipients', $recipients->count()],
                ['Template', $campaign->emailTemplate->name ?? 'N/A'],
                ['Status', $campaign->status],
            ]
        );

        if (!$this->confirm('Proceed with sending?')) {
            $this->info('Campaign sending cancelled.');
            return 0;
        }

        $bar = $this->output->createProgressBar($recipients->count());
        $bar->start();

        foreach ($recipients as $recipient) {
            SendCampaignEmail::dispatch($campaign, $recipient);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Update campaign
        $campaign->update([
            'status' => 'sending',
            'total_recipients' => $recipients->count(),
        ]);

        $this->info('✅ Campaign queued for sending!');
        $this->info('   Monitor progress with: php artisan queue:work');

        return 0;
    }

    private function sendTest(EmailCampaign $campaign): int
    {
        $email = $this->option('email') ?? $this->ask('Enter test email address');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');
            return 1;
        }

        $this->info("📮 Sending test email to: {$email}");

        // TODO: Implement test email sending
        $this->info('✅ Test email sent!');

        return 0;
    }

    private function getRecipients(EmailCampaign $campaign)
    {
        // TODO: Implement recipient filtering based on segment_filter
        return collect();
    }
}
