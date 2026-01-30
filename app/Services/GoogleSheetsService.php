<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;
    protected $sheetName;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.sheet_id');
        $this->sheetName = config('services.google.sheet_name');
        
        $this->initializeClient();
    }

    /**
     * Initialize Google Client
     */
    protected function initializeClient(): void
    {
        $credentialsPath = config('services.google.credentials_path');
        
        if (!$credentialsPath || !file_exists($credentialsPath)) {
            Log::warning('Google credentials file not found');
            return;
        }

        $this->client = new GoogleClient();
        $this->client->setApplicationName('Mayfair VMS');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
    }

    /**
     * Initialize sheet with headers if empty
     */
    public function initializeSheet(): bool
    {
        try {
            if (!$this->service) {
                return false;
            }

            // Check if headers exist
            $response = $this->service->spreadsheets_values->get(
                $this->spreadsheetId,
                "{$this->sheetName}!A1:Z1"
            );

            $values = $response->getValues();

            // If no headers, add them
            if (empty($values)) {
                $headers = [
                    'ID',
                    'Date & Time',
                    'Visitor Type',
                    'Name',
                    'Mobile',
                    'Email',
                    'Guest Type / Company / Project',
                    'Company Name / Department',
                    'Whom to Meet / Status',
                    'Accompanying Count',
                    'Status'
                ];

                $range = "{$this->sheetName}!A1:K1";
                $body = new ValueRange(['values' => [$headers]]);
                
                $this->service->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $range,
                    $body,
                    ['valueInputOption' => 'RAW']
                );

                Log::info('Google Sheet initialized with headers');
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets Initialize Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Append a visitor row to the sheet
     */
    public function appendVisitor(array $rowData): bool
    {
        try {
            if (!$this->service) {
                Log::warning('Google Sheets service not initialized');
                return false;
            }

            $range = "{$this->sheetName}!A:K";
            $body = new ValueRange(['values' => [$rowData]]);
            
            $params = ['valueInputOption' => 'RAW'];
            
            $result = $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );

            Log::info('Visitor appended to Google Sheets', [
                'updates' => $result->getUpdates()->getUpdatedCells()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets Append Error: ' . $e->getMessage(), [
                'data' => $rowData
            ]);
            return false;
        }
    }

    /**
     * Batch append multiple visitors
     */
    public function batchAppendVisitors(array $visitors): bool
    {
        try {
            if (!$this->service || empty($visitors)) {
                return false;
            }

            $range = "{$this->sheetName}!A:K";
            $body = new ValueRange(['values' => $visitors]);
            
            $params = ['valueInputOption' => 'RAW'];
            
            $result = $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );

            Log::info('Batch visitors appended to Google Sheets', [
                'count' => count($visitors),
                'updates' => $result->getUpdates()->getUpdatedCells()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets Batch Append Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all visitors from sheet
     */
    public function getAllVisitors(): array
    {
        try {
            if (!$this->service) {
                return [];
            }

            $response = $this->service->spreadsheets_values->get(
                $this->spreadsheetId,
                "{$this->sheetName}!A2:K"
            );

            return $response->getValues() ?? [];

        } catch (\Exception $e) {
            Log::error('Google Sheets Read Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear all data (except headers)
     */
    public function clearData(): bool
    {
        try {
            if (!$this->service) {
                return false;
            }

            $range = "{$this->sheetName}!A2:K";
            
            $this->service->spreadsheets_values->clear(
                $this->spreadsheetId,
                $range,
                new \Google\Service\Sheets\ClearValuesRequest()
            );

            Log::info('Google Sheet data cleared');
            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets Clear Error: ' . $e->getMessage());
            return false;
        }
    }
}
