<?php

namespace App\Console\Commands;

use App\Jobs\SyncVisitorToGoogleSheets;
use App\Models\Visitor;
use Illuminate\Console\Command;

class SyncUnsyncedVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:sync {--force : Force sync all visitors}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync unsynced visitors to Google Sheets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Syncing visitors to Google Sheets...');

        $query = Visitor::verified();

        if ($this->option('force')) {
            $this->warn('Force mode: Syncing all verified visitors');
            $visitors = $query->get();
        } else {
            $visitors = $query->where('synced_to_sheets', false)->get();
        }

        if ($visitors->isEmpty()) {
            $this->info('No visitors to sync.');
            return self::SUCCESS;
        }

        $this->info("Found {$visitors->count()} visitor(s) to sync");

        $bar = $this->output->createProgressBar($visitors->count());
        $bar->start();

        foreach ($visitors as $visitor) {
            SyncVisitorToGoogleSheets::dispatch($visitor);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('✓ Queued ' . $visitors->count() . ' visitor(s) for syncing');
        $this->line('Make sure queue worker is running: php artisan queue:work');

        return self::SUCCESS;
    }
}
