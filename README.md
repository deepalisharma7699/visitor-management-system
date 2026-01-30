# Mayfair Visitor Management System (VMS)

A comprehensive Laravel 11-based Visitor Management System with WhatsApp OTP verification and Google Sheets integration.

## Features

### 🎯 Core Features
- **Multi-Step Registration**: Intuitive 4-step visitor registration process
- **WhatsApp OTP Verification**: Secure verification via Twilio or Interakt
- **Email OTP Fallback**: 📧 Automatic email OTP when WhatsApp unavailable
- **International Phone Support**: 🌍 16+ countries with flag emojis (🇮🇳🇺🇸🇦🇪🇬🇧🇸🇬🇦🇺 etc.)
- **Comprehensive Logging**: 📋 Detailed logs for debugging and monitoring
- **Three Visitor Types**: Guest, Broker, and Customer workflows
- **Conditional Fields**: Dynamic form fields based on visitor type
- **Google Sheets Sync**: Automatic data synchronization using queues
- **Admin Dashboard**: Comprehensive visitor management panel
- **Mobile Responsive**: Works seamlessly on all devices

### 👥 Visitor Types

#### 1. Guest
- Visitor types: Vendor, Contractor, Family Member, Interview, Other
- Conditional company name (mandatory for Vendor/Contractor)
- Employee selection (whom to meet)
- Accompanying persons tracking (simple count or detailed entry)

#### 2. Broker
- Company name
- Department selection (Sales, Management, Accounts)
- Filtered employee list based on department

#### 3. Customer
- Interested project selection
- Simplified registration flow

## Tech Stack

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Livewire 3 + Tailwind CSS
- **Queue**: Laravel Queue (Database driver)
- **WhatsApp API**: Twilio / Interakt
- **Google API**: google/apiclient
- **Internationalization**: PHP intl extension for country flags

## Installation

### Prerequisites with **intl extension** enabled
- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js 18+ and NPM

### Step 1: Clone and Install Dependencies

```bash
cd e:\GitProjects\staging\mayfair_VMS

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Database Configuration

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mayfair_vms
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```bash
# Using MySQL CLI
mysql -u root -p
CREATE DATABASE mayfair_vms;
exit;

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

### Step 3: WhatsApp API Configuration

#### Option A: Using Twilio

1. Sign up at [Twilio](https://www.twilio.com/)
2. Get your Account SID, Auth Token, and WhatsApp number
3. Configure in `.env`:

```env
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

#### Option B: Using Interakt

1. Sign up at [Interakt](https://www.interakt.shop/)
2. Get your API key
3. Configure in `.env`:

```env
WHATSAPP_PROVIDER=interakt
INTERAKT_API_KEY=your_api_key
INTERAKT_BASE_URL=https://api.interakt.ai/v1
```

### Step 4: Google Sheets Configuration

1. **Create a Google Cloud Project**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project

2. **Enable Google Sheets API**
   - Navigate to "APIs & Services" > "Library"
   - Search for "Google Sheets API" and enable it

3. **Create Service Account**
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "Service Account"
   - Download the JSON key file

4. **Configure the Spreadsheet**
   - Create a new Google Sheet
   - Share it with the service account email (from JSON file)
   - Give "Editor" access
   - Copy the Spreadsheet ID from URL

5. **Update .env**:

```env
GOOGLE_APPLICATION_CREDENTIALS=path/to/your/credentials.json
GOOGLE_SHEET_ID=your_spreadsheet_id
GOOGLE_SHEET_NAME=Visitors
```

Example JSON path: `storage/app/google/credentials.json`

### Step 5: Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 6: Start Queue Worker

The queue worker is essential for Google Sheets sync:

```bash
# Start queue worker
php artisan queue:work

# Or use supervisor in production (recommended)
```

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage

### For Visitors

1. Navigate to `http://localhost:8000`
2. Select visitor type (Guest/Broker/Customer)
3. Enter name and mobile number
4. Verify OTP from WhatsApp
5. Complete additional details
6. Registration complete! Data synced to Google Sheets

### For Administrators

1. Navigate to `http://localhost:8000/admin/visitors`
2. View all visitor registrations
3. Filter by type, status, date
4. Check Google Sheets sync status
5. Manually trigger sync if needed
6. Check-out visitors

## Project Structure

```
mayfair_VMS/
├── app/
│   ├── Http/Controllers/
│   │   └── VisitorController.php
│   ├── Jobs/
│   │   └── SyncVisitorToGoogleSheets.php
│   ├── Livewire/
│   │   └── VisitorRegistration.php
│   ├── Models/
│   │   ├── Visitor.php
│   │   ├── Employee.php
│   │   └── Project.php
│   └── Services/
│       ├── WhatsAppService.php
│       └── GoogleSheetsService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── admin/
│       ├── visitor/
│       ├── livewire/
│       └── components/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
├── package.json
├── tailwind.config.js
└── vite.config.js
```

## Key Components

### Services

#### WhatsAppService
- OTP generation (4-digit)
- WhatsApp message sending (Twilio/Interakt)
- OTP verification
- Welcome messages

#### GoogleSheetsService
- Sheet initialization with headers
- Append visitor data
- Batch operations
- Auto-retry on failure

### Livewire Component

#### VisitorRegistration
- Multi-step form logic
- Real-time validation
- Conditional field rendering
- OTP management
- Queue dispatch

### Models

#### Visitor
- All visitor data
- Type-specific relationships
- Sheet data formatting
- OTP validation

#### Employee
- Staff directory
- Department filtering

#### Project
- Available projects
- Customer interests

## Queue Configuration

The system uses Laravel Queue for async Google Sheets sync:

```bash
# Run queue worker
php artisan queue:work

# Process jobs with retry
php artisan queue:work --tries=3

# Restart workers after code changes
php artisan queue:restart
```

### Production Queue Setup (Supervisor)

Create `/etc/supervisor/conf.d/mayfair-vms-worker.conf`:

```ini
[program:mayfair-vms-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/mayfair_VMS/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/mayfair_VMS/storage/logs/worker.log
```

## API Integrations

### Twilio WhatsApp API

**Endpoint**: Messages API
**Format**: WhatsApp Business API
**Rate Limits**: Check Twilio documentation

### Interakt WhatsApp API

**Endpoint**: `/messages`
**Format**: Template-based messaging
**Authentication**: Basic Auth with API key

### Google Sheets API

**Service**: Google Sheets API v4
**Authentication**: Service Account (OAuth 2.0)
**Scopes**: `https://www.googleapis.com/auth/spreadsheets`

## Troubleshooting

### OTP Not Sending

1. Check WhatsApp API credentials
2. Verify mobile number format (+91...)
3. Check API logs: `storage/logs/laravel.log`
4. Test API connection independently

### Google Sheets Not Syncing

1. Verify queue worker is running: `ps aux | grep queue:work`
2. Check credentials file exists and is readable
3. Verify service account has Editor access to sheet
4. Check failed jobs: `php artisan queue:failed`
5. Retry failed jobs: `php artisan queue:retry all`

### Database Errors

1. Check database connection in `.env`
2. Ensure migrations are run: `php artisan migrate:status`
3. Re-run migrations: `php artisan migrate:fresh --seed`

## Security Considerations

- ✅ CSRF protection enabled
- ✅ OTP expiration (5 minutes)
- ✅ Mobile number validation
- ✅ Input sanitization
- ✅ Queue retry limits
- ✅ Google credentials secured

## Performance Optimization

- Queue-based Google Sheets sync (no user latency)
- Database indexing on key fields
- Cached employee/project lists
- Livewire for SPA-like experience
- Tailwind CSS purging in production

## Testing

```bash
# Run tests
php artisan test

# With coverage
php artisan test --coverage
```

## Deployment

### Production Checklist

1. ✅ Set `APP_ENV=production` and `APP_DEBUG=false`
2. ✅ Configure production database
3. ✅ Set up SSL certificate
4. ✅ Configure queue worker with Supervisor
5. ✅ Set up scheduled tasks (if needed)
6. ✅ Build production assets: `npm run build`
7. ✅ Optimize Laravel: `php artisan optimize`
8. ✅ Set proper file permissions
9. ✅ Configure backup strategy
10. ✅ Set up monitoring and logging

## 📚 Documentation

- [Quick Start Guide](QUICKSTART.md) - Get started in 5 minutes
- [Testing Guide](TESTING_GUIDE.md) - 🧪 How to test without API keys
- [Email & Logging Setup](EMAIL_LOGGING_SETUP.md) - 📧 Email OTP and debugging
- [Installation Guide](INSTALLATION.md) - Detailed setup instructions
- [Google Sheets Setup](GOOGLE_SHEETS_SETUP.md) - Google API configuration
- [WhatsApp Templates](WHATSAPP_TEMPLATES.md) - Message templates with examples
- [International Phone Support](INTERNATIONAL_PHONE_SUPPORT.md) - 🌍 Multi-country setup
- [Features Documentation](FEATURES.md) - Complete feature list
- [Project Summary](PROJECT_SUMMARY.md) - Technical overview

## License

MIT License

## Support

For issues and questions, please contact your development team.

---

**Built with ❤️ using Laravel 11**
