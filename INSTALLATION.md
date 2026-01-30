# Installation & Setup Instructions

This document provides detailed step-by-step instructions to install and configure the Mayfair Visitor Management System.

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Installation Steps](#installation-steps)
3. [WhatsApp Integration](#whatsapp-integration)
4. [Google Sheets Integration](#google-sheets-integration)
5. [Running the Application](#running-the-application)
6. [Testing](#testing)
7. [Troubleshooting](#troubleshooting)

---

## System Requirements

### Required Software

| Software | Minimum Version | Recommended |
|----------|----------------|-------------|
| PHP | 8.2 | 8.3+ |
| Composer | 2.5+ | Latest |
| MySQL | 5.7 | 8.0+ |
| Node.js | 18.x | 20.x LTS |
| NPM | 9.x | 10.x |

### PHP Extensions Required

```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
```

Check your PHP extensions:
```bash
php -m
```

---

## Installation Steps

### 1. Navigate to Project Directory

```bash
cd e:\GitProjects\staging\mayfair_VMS
```

### 2. Install PHP Dependencies

```bash
composer install
```

**Expected Output:**
```
Installing dependencies from lock file
Package operations: X installs, 0 updates, 0 removals
  - Installing vendor/package...
```

If you encounter memory issues:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

**Expected Output:**
```
added XXX packages in XXs
```

### 4. Environment Configuration

Copy the example environment file:
```bash
copy .env.example .env
```

Generate application key:
```bash
php artisan key:generate
```

### 5. Configure Environment Variables

Edit `.env` file:

```env
# Application
APP_NAME="Visitor Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mayfair_vms
DB_USERNAME=root
DB_PASSWORD=your_password_here

# Queue
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
```

### 6. Create Database

**Using MySQL Command Line:**
```bash
mysql -u root -p
```

```sql
CREATE DATABASE mayfair_vms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
EXIT;
```

**Or using PHP:**
```bash
php -r "
$conn = new mysqli('127.0.0.1', 'root', 'your_password', '', 3306);
if ($conn->query('CREATE DATABASE mayfair_vms')) {
    echo 'Database created successfully';
}
"
```

### 7. Run Migrations

```bash
php artisan migrate
```

**Expected Output:**
```
Migration table created successfully.
Migrating: 2024_01_01_000001_create_visitors_table
Migrated:  2024_01_01_000001_create_visitors_table
...
```

### 8. Seed Database

```bash
php artisan db:seed
```

**This will create:**
- 9 Employees (Sales, Management, Accounts, HR, IT)
- 5 Projects (Residential & Commercial)

### 9. Build Frontend Assets

**For Development:**
```bash
npm run dev
```

**For Production:**
```bash
npm run build
```

### 10. Create Storage Link

```bash
php artisan storage:link
```

---

## WhatsApp Integration

### Option A: Twilio Setup

#### Step 1: Create Twilio Account

1. Visit [Twilio Console](https://console.twilio.com/)
2. Sign up for free account
3. Verify your phone number

#### Step 2: Get WhatsApp Sandbox

1. Navigate to "Messaging" > "Try it out" > "Send a WhatsApp message"
2. Follow instructions to join sandbox
3. Note your sandbox number

#### Step 3: Get Credentials

1. Go to Dashboard
2. Copy **Account SID**
3. Copy **Auth Token**

#### Step 4: Configure .env

```env
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

#### Step 5: Test Integration

```bash
php artisan tinker
```

```php
$service = app(\App\Services\WhatsAppService::class);
$otp = $service->generateOTP();
echo "OTP: $otp\n";
$service->sendOTP('9876543210', $otp);
exit
```

### Option B: Interakt Setup

#### Step 1: Create Interakt Account

1. Visit [Interakt](https://www.interakt.shop/)
2. Sign up for account
3. Complete verification

#### Step 2: Get API Key

1. Navigate to Settings > API
2. Generate new API key
3. Copy the key

#### Step 3: Configure .env

```env
WHATSAPP_PROVIDER=interakt
INTERAKT_API_KEY=your_api_key_here
INTERAKT_BASE_URL=https://api.interakt.ai/v1
```

#### Step 4: Create Message Template

Create an OTP template in Interakt dashboard:
- Template name: `mayfair_vms_otp_verification_yc`
- Language: English
- Template content: Your OTP is {{1}}. Valid for {{2}} minutes.

---

## Google Sheets Integration

### Step 1: Google Cloud Setup

1. **Create Project**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create new project: "Mayfair VMS"

2. **Enable Google Sheets API**
   - Navigate to "APIs & Services" > "Library"
   - Search "Google Sheets API"
   - Click "Enable"

3. **Create Service Account**
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "Service Account"
   - Name: `mayfair-vms-service`
   - Create and download JSON key

### Step 2: Set Up Google Sheet

1. Create new Google Sheet
2. Name it: "Mayfair VMS - Visitors"
3. Copy Spreadsheet ID from URL:
   ```
   https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit
   ```

### Step 3: Share with Service Account

1. Open the JSON credentials file
2. Copy the `client_email` value
3. Share your Google Sheet with this email
4. Grant "Editor" permission

### Step 4: Configure Application

1. **Create credentials directory:**
   ```bash
   mkdir storage\app\google
   ```

2. **Copy credentials file:**
   ```bash
   copy path\to\downloaded\credentials.json storage\app\google\credentials.json
   ```

3. **Update .env:**
   ```env
   GOOGLE_APPLICATION_CREDENTIALS=storage/app/google/credentials.json
   GOOGLE_SHEET_ID=your_spreadsheet_id_here
   GOOGLE_SHEET_NAME=Visitors
   ```

4. **Set file permissions:**
   ```bash
   icacls storage\app\google\credentials.json /grant:r "%USERNAME%:(R)"
   ```

### Step 5: Initialize Sheet

```bash
php artisan sheets:init
```

**Expected Output:**
```
Initializing Google Sheet...
✓ Google Sheet initialized successfully!
```

---

## Running the Application

### Development Environment

Open **3 separate terminal windows**:

#### Terminal 1: Start Web Server
```bash
php artisan serve
```
Access at: http://localhost:8000

#### Terminal 2: Start Queue Worker
```bash
php artisan queue:work
```

#### Terminal 3: Build Assets (Dev Mode)
```bash
npm run dev
```

### Production Environment

```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Build assets
npm run build

# Start queue with supervisor (see supervisor config below)
```

---

## Testing

### Manual Testing

1. **Visit Registration Page**
   ```
   http://localhost:8000
   ```

2. **Register Test Visitor**
   - Type: Customer
   - Name: Test User
   - Mobile: 9876543210
   - OTP: Check `storage/logs/laravel.log`

3. **Check Admin Panel**
   ```
   http://localhost:8000/admin/visitors
   ```

4. **Verify Google Sheet**
   - Open your Google Sheet
   - Check for new row with visitor data

### Command Line Testing

```bash
# Test database connection
php artisan migrate:status

# Test Google Sheets
php artisan sheets:init

# Test queue
php artisan queue:work --once

# Sync visitors manually
php artisan sheets:sync
```

---

## Troubleshooting

### Issue: Composer Install Fails

**Error:** `Your requirements could not be resolved`

**Solution:**
```bash
composer update --ignore-platform-reqs
# or
composer install --ignore-platform-reqs
```

### Issue: NPM Install Fails

**Error:** `EACCES: permission denied`

**Solution:**
```bash
# Clear npm cache
npm cache clean --force

# Use administrator terminal
# Re-run npm install
```

### Issue: Database Connection Failed

**Error:** `SQLSTATE[HY000] [1045] Access denied`

**Solution:**
1. Verify MySQL is running
2. Check credentials in `.env`
3. Test connection:
   ```bash
   mysql -u root -p
   USE mayfair_vms;
   ```

### Issue: Queue Not Processing

**Error:** Jobs remain in `jobs` table

**Solution:**
```bash
# Check queue worker is running
php artisan queue:work

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

### Issue: Google Sheets Not Syncing

**Error:** `The caller does not have permission`

**Solution:**
1. Verify service account email is shared with spreadsheet
2. Check Editor permission is granted
3. Wait 2-3 minutes for permissions to propagate
4. Retry:
   ```bash
   php artisan sheets:sync --force
   ```

### Issue: OTP Not Received

**Solution:**
1. Check logs: `storage/logs/laravel.log`
2. Verify WhatsApp API credentials
3. Test API directly:
   ```bash
   php artisan tinker
   ```
   ```php
   $service = app(\App\Services\WhatsAppService::class);
   $service->sendOTP('9876543210', '1234');
   ```

### Issue: Styles Not Loading

**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Rebuild assets
npm run build

# Check public/build directory exists
```

---

## Supervisor Configuration (Production)

Create `/etc/supervisor/conf.d/mayfair-vms.conf`:

```ini
[program:mayfair-vms-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/mayfair_VMS/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/mayfair_VMS/storage/logs/worker.log
stopwaitsecs=3600
```

Activate:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mayfair-vms-queue:*
```

---

## Verification Checklist

Before going live, verify:

- [ ] Database migrations completed
- [ ] Sample data seeded
- [ ] WhatsApp API configured and tested
- [ ] Google Sheets configured and tested
- [ ] Queue worker running
- [ ] Assets compiled
- [ ] `.env` properly configured
- [ ] Storage permissions set
- [ ] Google credentials secured
- [ ] Backup strategy in place

---

## Getting Help

1. **Check Logs**
   - Laravel: `storage/logs/laravel.log`
   - Queue: `storage/logs/worker.log`

2. **Documentation**
   - [README.md](README.md) - Full documentation
   - [QUICKSTART.md](QUICKSTART.md) - Quick start guide
   - [GOOGLE_SHEETS_SETUP.md](GOOGLE_SHEETS_SETUP.md) - Sheets guide

3. **Common Commands**
   ```bash
   # Clear all caches
   php artisan optimize:clear
   
   # View queue status
   php artisan queue:work --once
   
   # Check migrations
   php artisan migrate:status
   
   # Test Google Sheets
   php artisan sheets:init
   ```

---

**Installation Complete! 🎉**

Access your VMS at: http://localhost:8000
