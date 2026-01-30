<?php

namespace App\Console\Commands;

use App\Models\Visitor;
use Illuminate\Console\Command;

class AutoCheckoutVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitors:auto-checkout 
                            {--dry-run : Preview which visitors would be checked out without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checkout visitors from previous days who forgot to check out';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('🔍 Searching for expired visits (Dubai Time: ' . dubai_now()->format('d-M-Y h:i A') . ')...');
        $this->newLine();

        // Get all expired visits
        $expiredVisitors = Visitor::expiredVisits()->get();

        if ($expiredVisitors->isEmpty()) {
            $this->info('✓ No expired visits found. All visitors have checked out properly.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$expiredVisitors->count()} visitor(s) with expired visits:");
        $this->newLine();

        // Display table of expired visitors
        $tableData = [];
        foreach ($expiredVisitors as $visitor) {
            $tableData[] = [
                'ID' => $visitor->id,
                'Name' => $visitor->name,
                'Mobile' => $visitor->mobile,
                'Type' => ucfirst($visitor->visitor_type),
                'Checked In' => format_date_dubai($visitor->checked_in_at),
                'Hours Elapsed' => round($visitor->visit_duration, 1) . 'h',
            ];
        }

        $this->table(
            ['ID', 'Name', 'Mobile', 'Type', 'Checked In', 'Hours Elapsed'],
            $tableData
        );

        if ($isDryRun) {
            $this->newLine();
            $this->warn('🔸 DRY RUN MODE - No changes will be made');
            $this->info('Run without --dry-run to actually checkout these visitors.');
            return Command::SUCCESS;
        }

        // Confirm before proceeding
        if (!$this->confirm('Do you want to auto-checkout these visitors?', true)) {
            $this->warn('Operation cancelled.');
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('⏳ Processing auto-checkout...');

        $successCount = 0;
        $failCount = 0;

        // Process each expired visitor
        $progressBar = $this->output->createProgressBar($expiredVisitors->count());
        $progressBar->start();

        foreach ($expiredVisitors as $visitor) {
            if ($visitor->autoCheckout()) {
                $successCount++;
            } else {
                $failCount++;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        if ($successCount > 0) {
            $this->info("✓ Successfully auto-checked-out {$successCount} visitor(s)");
        }

        if ($failCount > 0) {
            $this->error("✗ Failed to checkout {$failCount} visitor(s)");
        }

        $this->newLine();
        $this->comment('Note: Visitors were checked out at 11:59 PM of their check-in day.');

        return Command::SUCCESS;
    }
}
