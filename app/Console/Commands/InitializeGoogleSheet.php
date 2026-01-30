<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class InitializeGoogleSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Google Sheet with headers';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetsService $sheetsService): int
    {
        $this->info('Initializing Google Sheet...');

        if (!config('services.google.credentials_path')) {
            $this->error('Google credentials not configured!');
            $this->line('Please set GOOGLE_APPLICATION_CREDENTIALS in .env');
            return self::FAILURE;
        }

        if (!config('services.google.sheet_id')) {
            $this->error('Google Sheet ID not configured!');
            $this->line('Please set GOOGLE_SHEET_ID in .env');
            return self::FAILURE;
        }

        try {
            $success = $sheetsService->initializeSheet();

            if ($success) {
                $this->info('✓ Google Sheet initialized successfully!');
                $this->line('Sheet: ' . config('services.google.sheet_id'));
                $this->line('Name: ' . config('services.google.sheet_name'));
                return self::SUCCESS;
            } else {
                $this->error('Failed to initialize Google Sheet');
                $this->line('Check logs for more details: storage/logs/laravel.log');
                return self::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
