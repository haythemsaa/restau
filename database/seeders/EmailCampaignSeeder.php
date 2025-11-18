<?php

namespace Database\Seeders;

use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailCampaignSeeder extends Seeder
{
    public function run(): void
    {
        $welcomeTemplate = EmailTemplate::where('category', 'welcome')->first();
        $winbackTemplate = EmailTemplate::where('category', 'winback')->first();
        $vipTemplate = EmailTemplate::where('category', 'vip')->first();

        $campaigns = [
            [
                'name' => 'Campagne Bienvenue 2024',
                'email_template_id' => $welcomeTemplate?->id,
                'segment_filter' => json_encode([
                    'created_after' => now()->subDays(7)->format('Y-m-d'),
                    'visit_count' => 1,
                ]),
                'scheduled_at' => now()->addHour(),
                'status' => 'scheduled',
                'total_recipients' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
            ],
            [
                'name' => 'Réactivation Clients Inactifs',
                'email_template_id' => $winbackTemplate?->id,
                'segment_filter' => json_encode([
                    'days_since_last_visit' => ['min' => 30],
                    'visit_count' => ['min' => 3],
                ]),
                'scheduled_at' => now()->addDays(2),
                'status' => 'draft',
                'total_recipients' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
            ],
            [
                'name' => 'Programme VIP - Invitation',
                'email_template_id' => $vipTemplate?->id,
                'segment_filter' => json_encode([
                    'tier' => ['vip', 'super_vip'],
                ]),
                'scheduled_at' => null,
                'status' => 'draft',
                'total_recipients' => 0,
                'sent_count' => 0,
                'opened_count' => 0,
                'clicked_count' => 0,
            ],
        ];

        foreach ($campaigns as $campaign) {
            EmailCampaign::create($campaign);
        }
    }
}
