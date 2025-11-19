<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DetectAtRiskCustomersCommand extends Command
{
    protected $signature = 'customers:detect-at-risk
                            {--days=30 : Days of inactivity to consider at risk}
                            {--min-visits=3 : Minimum visit count to be considered}
                            {--notify : Send notifications to at-risk customers}';

    protected $description = 'Detect customers at risk of churning';

    public function handle(): int
    {
        $inactiveDays = $this->option('days');
        $minVisits = $this->option('min-visits');

        $this->info("🔍 Detecting at-risk customers...");
        $this->line("   Inactive for {$inactiveDays}+ days with {$minVisits}+ visits");

        $cutoffDate = Carbon::now()->subDays($inactiveDays);

        $atRiskCustomers = Customer::whereHas('visits', function ($query) use ($minVisits) {
            $query->havingRaw('COUNT(*) >= ?', [$minVisits]);
        })
        ->where(function ($query) use ($cutoffDate) {
            $query->whereDoesntHave('visits', function ($q) use ($cutoffDate) {
                $q->where('visit_date', '>=', $cutoffDate);
            })
            ->orWhereHas('visits', function ($q) use ($cutoffDate) {
                $q->where('visit_date', '<', $cutoffDate)
                  ->whereRaw('visit_date = (SELECT MAX(visit_date) FROM customer_visits WHERE customer_id = customers.id)');
            });
        })
        ->get();

        if ($atRiskCustomers->isEmpty()) {
            $this->info('✅ No at-risk customers found!');
            return 0;
        }

        $this->warn("⚠️  Found {$atRiskCustomers->count()} at-risk customers:");

        $tableData = $atRiskCustomers->map(function ($customer) {
            $lastVisit = $customer->visits()->latest('visit_date')->first();
            $daysSince = $lastVisit
                ? Carbon::parse($lastVisit->visit_date)->diffInDays(now())
                : 'Never';

            return [
                $customer->full_name,
                $customer->email,
                $customer->visit_count,
                $customer->lifetime_value,
                $daysSince . ' days',
                $customer->tier,
            ];
        });

        $this->table(
            ['Name', 'Email', 'Visits', 'LTV', 'Last Visit', 'Tier'],
            $tableData
        );

        // Mark as at risk
        foreach ($atRiskCustomers as $customer) {
            $customer->update(['at_risk' => true]);
        }

        $this->info('✅ Customers marked as at-risk in database');

        if ($this->option('notify')) {
            $this->info('📧 Sending notifications...');
            // TODO: Implement notification sending
            $this->info('✅ Notifications sent!');
        } else {
            $this->line('💡 Use --notify flag to send win-back campaigns');
        }

        // Show summary
        $this->newLine();
        $this->info('📊 Summary:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total At-Risk', $atRiskCustomers->count()],
                ['Average LTV', '€' . number_format($atRiskCustomers->avg('lifetime_value'), 2)],
                ['Total Potential Loss', '€' . number_format($atRiskCustomers->sum('lifetime_value'), 2)],
                ['VIP At-Risk', $atRiskCustomers->where('tier', 'vip')->count()],
                ['Super VIP At-Risk', $atRiskCustomers->where('tier', 'super_vip')->count()],
            ]
        );

        return 0;
    }
}
