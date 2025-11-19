<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\EmailCampaign;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateReportCommand extends Command
{
    protected $signature = 'report:generate
                            {type : Report type: daily, weekly, monthly, custom}
                            {--from= : Start date (Y-m-d)}
                            {--to= : End date (Y-m-d)}
                            {--export= : Export format: pdf, csv, json}';

    protected $description = 'Generate business reports';

    public function handle(): int
    {
        $type = $this->argument('type');

        $this->info("📊 Generating {$type} report...");

        $dateRange = $this->getDateRange($type);

        $report = $this->generateReportData($dateRange);

        $this->displayReport($report);

        if ($exportFormat = $this->option('export')) {
            $this->exportReport($report, $exportFormat);
        }

        return 0;
    }

    private function getDateRange(string $type): array
    {
        if ($type === 'custom') {
            $from = $this->option('from') ?? $this->ask('From date (Y-m-d)');
            $to = $this->option('to') ?? $this->ask('To date (Y-m-d)');

            return [
                'from' => Carbon::parse($from),
                'to' => Carbon::parse($to),
            ];
        }

        $now = Carbon::now();

        return match ($type) {
            'daily' => [
                'from' => $now->copy()->startOfDay(),
                'to' => $now->copy()->endOfDay(),
            ],
            'weekly' => [
                'from' => $now->copy()->startOfWeek(),
                'to' => $now->copy()->endOfWeek(),
            ],
            'monthly' => [
                'from' => $now->copy()->startOfMonth(),
                'to' => $now->copy()->endOfMonth(),
            ],
            default => throw new \InvalidArgumentException("Invalid report type: {$type}"),
        };
    }

    private function generateReportData(array $dateRange): array
    {
        $from = $dateRange['from'];
        $to = $dateRange['to'];

        // Customer metrics
        $newCustomers = Customer::whereBetween('created_at', [$from, $to])->count();
        $totalCustomers = Customer::count();
        $vipCustomers = Customer::where('tier', 'vip')->count();
        $superVipCustomers = Customer::where('tier', 'super_vip')->count();
        $atRiskCustomers = Customer::where('at_risk', true)->count();

        // Revenue metrics (from visits)
        $visits = \App\Models\CustomerVisit::whereBetween('visit_date', [$from, $to])->get();
        $totalRevenue = $visits->sum('total_amount');
        $averageOrderValue = $visits->avg('total_amount');
        $totalVisits = $visits->count();

        // Campaign metrics
        $campaigns = EmailCampaign::whereBetween('created_at', [$from, $to])->get();
        $campaignsSent = $campaigns->where('status', 'sent')->count();
        $totalRecipients = $campaigns->sum('total_recipients');
        $totalOpened = $campaigns->sum('opened_count');
        $totalClicked = $campaigns->sum('clicked_count');

        $openRate = $totalRecipients > 0 ? ($totalOpened / $totalRecipients) * 100 : 0;
        $clickRate = $totalRecipients > 0 ? ($totalClicked / $totalRecipients) * 100 : 0;

        return [
            'period' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'customers' => [
                'total' => $totalCustomers,
                'new' => $newCustomers,
                'vip' => $vipCustomers,
                'super_vip' => $superVipCustomers,
                'at_risk' => $atRiskCustomers,
            ],
            'revenue' => [
                'total' => $totalRevenue,
                'average_order' => $averageOrderValue,
                'total_visits' => $totalVisits,
            ],
            'campaigns' => [
                'sent' => $campaignsSent,
                'recipients' => $totalRecipients,
                'opened' => $totalOpened,
                'clicked' => $totalClicked,
                'open_rate' => round($openRate, 2),
                'click_rate' => round($clickRate, 2),
            ],
        ];
    }

    private function displayReport(array $report): void
    {
        $this->newLine();
        $this->info('📅 Report Period: ' . $report['period']['from'] . ' to ' . $report['period']['to']);
        $this->newLine();

        $this->info('👥 CUSTOMERS');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Customers', number_format($report['customers']['total'])],
                ['New Customers', number_format($report['customers']['new'])],
                ['VIP Customers', number_format($report['customers']['vip'])],
                ['Super VIP', number_format($report['customers']['super_vip'])],
                ['At Risk', number_format($report['customers']['at_risk'])],
            ]
        );

        $this->newLine();
        $this->info('💰 REVENUE');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Revenue', '€' . number_format($report['revenue']['total'], 2)],
                ['Total Visits', number_format($report['revenue']['total_visits'])],
                ['Average Order', '€' . number_format($report['revenue']['average_order'], 2)],
            ]
        );

        $this->newLine();
        $this->info('📧 CAMPAIGNS');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Campaigns Sent', number_format($report['campaigns']['sent'])],
                ['Recipients', number_format($report['campaigns']['recipients'])],
                ['Opened', number_format($report['campaigns']['opened'])],
                ['Clicked', number_format($report['campaigns']['clicked'])],
                ['Open Rate', $report['campaigns']['open_rate'] . '%'],
                ['Click Rate', $report['campaigns']['click_rate'] . '%'],
            ]
        );
    }

    private function exportReport(array $report, string $format): void
    {
        $filename = 'report_' . Carbon::now()->format('Y-m-d_His') . '.' . $format;

        $this->info("📤 Exporting to {$filename}...");

        match ($format) {
            'json' => file_put_contents($filename, json_encode($report, JSON_PRETTY_PRINT)),
            'csv' => $this->exportToCsv($report, $filename),
            'pdf' => $this->info('PDF export not yet implemented'),
            default => $this->error("Unknown export format: {$format}"),
        };

        $this->info("✅ Report exported to {$filename}");
    }

    private function exportToCsv(array $report, string $filename): void
    {
        $handle = fopen($filename, 'w');

        fputcsv($handle, ['Report Period', $report['period']['from'] . ' to ' . $report['period']['to']]);
        fputcsv($handle, []);

        fputcsv($handle, ['CUSTOMERS']);
        foreach ($report['customers'] as $key => $value) {
            fputcsv($handle, [ucwords(str_replace('_', ' ', $key)), $value]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['REVENUE']);
        foreach ($report['revenue'] as $key => $value) {
            fputcsv($handle, [ucwords(str_replace('_', ' ', $key)), $value]);
        }
        fputcsv($handle, []);

        fputcsv($handle, ['CAMPAIGNS']);
        foreach ($report['campaigns'] as $key => $value) {
            fputcsv($handle, [ucwords(str_replace('_', ' ', $key)), $value]);
        }

        fclose($handle);
    }
}
