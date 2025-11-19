<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\RFMAnalysisService;
use Illuminate\Console\Command;

class CalculateRFMScoresCommand extends Command
{
    protected $signature = 'customers:calculate-rfm
                            {--customer= : Specific customer ID to calculate}
                            {--force : Force recalculation even if recently calculated}';

    protected $description = 'Calculate RFM scores for all customers or a specific customer';

    public function handle(RFMAnalysisService $rfmService): int
    {
        $this->info('🔢 Calculating RFM scores...');

        $customerId = $this->option('customer');

        if ($customerId) {
            $customer = Customer::find($customerId);

            if (!$customer) {
                $this->error("Customer with ID {$customerId} not found.");
                return 1;
            }

            $this->calculateForCustomer($customer, $rfmService);
        } else {
            $this->calculateForAllCustomers($rfmService);
        }

        $this->info('✅ RFM calculation completed!');

        return 0;
    }

    private function calculateForCustomer(Customer $customer, RFMAnalysisService $rfmService): void
    {
        $this->line("Calculating for: {$customer->full_name}");

        $scores = $rfmService->calculateRFMScore($customer);

        $this->table(
            ['Metric', 'Score'],
            [
                ['Recency', $scores['recency_score']],
                ['Frequency', $scores['frequency_score']],
                ['Monetary', $scores['monetary_score']],
                ['Total', $scores['total_score']],
                ['Segment', $scores['segment']],
            ]
        );
    }

    private function calculateForAllCustomers(RFMAnalysisService $rfmService): void
    {
        $customers = Customer::all();
        $bar = $this->output->createProgressBar($customers->count());

        $bar->start();

        $stats = [
            'champions' => 0,
            'loyal' => 0,
            'potential' => 0,
            'at_risk' => 0,
            'lost' => 0,
        ];

        foreach ($customers as $customer) {
            $scores = $rfmService->calculateRFMScore($customer);

            // Update stats
            $segment = strtolower(str_replace(' ', '_', $scores['segment']));
            if (isset($stats[$segment])) {
                $stats[$segment]++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('📊 Segmentation Results:');
        $this->table(
            ['Segment', 'Count'],
            [
                ['Champions', $stats['champions']],
                ['Loyal Customers', $stats['loyal']],
                ['Potential Loyalists', $stats['potential']],
                ['At Risk', $stats['at_risk']],
                ['Lost', $stats['lost']],
            ]
        );
    }
}
