# Google Sheets Setup Guide

This guide will help you set up Google Sheets API integration for the Mayfair VMS.

## Step 1: Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click on the project dropdown at the top
3. Click "New Project"
4. Enter project name: "Mayfair VMS"
5. Click "Create"

## Step 2: Enable Google Sheets API

1. In the Google Cloud Console, go to "APIs & Services" > "Library"
2. Search for "Google Sheets API"
3. Click on it and press "Enable"

## Step 3: Create Service Account

1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "Service Account"
3. Fill in the details:
   - **Service account name**: mayfair-vms-service
   - **Service account ID**: (auto-generated)
   - **Description**: Service account for Mayfair VMS
4. Click "Create and Continue"
5. Skip the optional steps and click "Done"

## Step 4: Create and Download Service Account Key

1. In the Credentials page, find your service account
2. Click on the service account email
3. Go to the "Keys" tab
4. Click "Add Key" > "Create new key"
5. Select "JSON" format
6. Click "Create"
7. The JSON file will be downloaded automatically
8. **Important**: Keep this file secure!

## Step 5: Set Up Google Sheet

1. Go to [Google Sheets](https://sheets.google.com/)
2. Create a new spreadsheet
3. Name it: "Mayfair VMS - Visitors"
4. Note the Spreadsheet ID from the URL:
   ```
   https://docs.google.com/spreadsheets/d/SPREADSHEET_ID_HERE/edit
   ```

## Step 6: Share Spreadsheet with Service Account

1. In your Google Sheet, click the "Share" button
2. Copy the service account email from the JSON file:
   ```json
   {
     "client_email": "mayfair-vms-service@project-id.iam.gserviceaccount.com"
   }
   ```
3. Paste this email in the share dialog
4. Set permission to "Editor"
5. Uncheck "Notify people"
6. Click "Share"

## Step 7: Configure Laravel Application

1. Create a directory for credentials:
   ```bash
   mkdir -p storage/app/google
   ```

2. Copy the downloaded JSON file:
   ```bash
   copy Downloads\your-credentials.json storage\app\google\credentials.json
   ```

3. Update `.env` file:
   ```env
   GOOGLE_APPLICATION_CREDENTIALS=storage/app/google/credentials.json
   GOOGLE_SHEET_ID=your_spreadsheet_id_here
   GOOGLE_SHEET_NAME=Visitors
   ```

## Step 8: Test the Integration

1. Start the queue worker:
   ```bash
   php artisan queue:work
   ```

2. Initialize the Google Sheet:
   ```bash
   php artisan tinker
   ```
   
   Then in tinker:
   ```php
   $service = app(\App\Services\GoogleSheetsService::class);
   $service->initializeSheet();
   exit
   ```

3. Register a test visitor through the web interface
4. Check your Google Sheet - it should have headers and the visitor data!

## Troubleshooting

### Error: "Unable to parse credentials"
- Verify the JSON file path is correct
- Check file permissions (should be readable)
- Ensure the JSON file is valid

### Error: "The caller does not have permission"
- Verify service account email is shared with the spreadsheet
- Check that Editor permission is granted
- Wait a few minutes for permissions to propagate

### Error: "Spreadsheet not found"
- Verify the Spreadsheet ID is correct
- Check that the sheet is shared with service account
- Ensure the sheet exists and is accessible

### Data not syncing
- Ensure queue worker is running: `php artisan queue:work`
- Check logs: `storage/logs/laravel.log`
- View failed jobs: `php artisan queue:failed`

## Sheet Structure

The system will create these columns automatically:

| Column | Description |
|--------|-------------|
| ID | Visitor registration ID |
| Date & Time | Registration timestamp |
| Visitor Type | Guest/Broker/Customer |
| Name | Full name |
| Mobile | Phone number |
| Email | Email address |
| Guest Type / Company / Project | Context-specific field |
| Company Name / Department | Context-specific field |
| Whom to Meet / Status | Context-specific field |
| Accompanying Count | Number of companions |
| Status | Current visitor status |

## Security Best Practices

1. **Never commit credentials to Git**
   - Add to `.gitignore`:
     ```
     /storage/app/google/
     ```

2. **Set proper file permissions**:
   ```bash
   chmod 600 storage/app/google/credentials.json
   ```

3. **Rotate credentials periodically**
   - Create new service account key
   - Update configuration
   - Delete old key

4. **Monitor API usage**
   - Check Google Cloud Console for quota
   - Set up alerts for unusual activity

## API Quotas

Google Sheets API has the following quotas:

- **Read requests**: 300 per minute per project
- **Write requests**: 300 per minute per project
- **Per user per minute**: 60 requests

For higher limits, consider:
- Batching operations
- Caching data
- Requesting quota increase

## Advanced Configuration

### Multiple Sheets

To use multiple sheets (e.g., separate sheets per month):

1. Update `.env`:
   ```env
   GOOGLE_SHEET_NAME=Visitors_December_2024
   ```

2. Or dynamically in code:
   ```php
   $service = new GoogleSheetsService();
   $service->sheetName = 'Visitors_' . now()->format('F_Y');
   ```

### Batch Sync

To sync multiple visitors at once:

```php
use App\Models\Visitor;
use App\Services\GoogleSheetsService;

$visitors = Visitor::unsynced()->limit(100)->get();
$rows = $visitors->map(fn($v) => $v->toSheetRow())->toArray();

$service = new GoogleSheetsService();
$service->batchAppendVisitors($rows);
```

## Backup Strategy

1. **Enable version history** in Google Sheets
2. **Export data periodically**:
   - File > Download > CSV
3. **Set up automated backups** using Google Takeout
4. **Keep database as primary source of truth**

---

**Need help?** Check the main [README.md](README.md) or contact support.
