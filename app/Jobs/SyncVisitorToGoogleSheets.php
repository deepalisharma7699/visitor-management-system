<?php

namespace App\Jobs;

use App\Models\Visitor;
use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncVisitorToGoogleSheets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * The visitor instance.
     *
     * @var \App\Models\Visitor
     */
    protected $visitor;

    /**
     * Create a new job instance.
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleSheetsService $sheetsService): void
    {
        try {
            // Get visitor data formatted for sheets
            $rowData = $this->visitor->toSheetRow();

            // Append to Google Sheets
            $success = $sheetsService->appendVisitor($rowData);

            if ($success) {
                // Mark as synced
                $this->visitor->update([
                    'synced_to_sheets' => true,
                    'synced_at' => now(),
                ]);

                Log::info('Visitor synced to Google Sheets', [
                    'visitor_id' => $this->visitor->id,
                    'name' => $this->visitor->name,
                ]);
            } else {
                throw new \Exception('Failed to append visitor to Google Sheets');
            }

        } catch (\Exception $e) {
            Log::error('Sync to Google Sheets Failed', [
                'visitor_id' => $this->visitor->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Sync to Google Sheets Failed Permanently', [
            'visitor_id' => $this->visitor->id,
            'name' => $this->visitor->name,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify admin or mark for manual intervention
    }
}
