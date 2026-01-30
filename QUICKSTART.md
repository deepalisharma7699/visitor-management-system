# Quick Start Guide - Mayfair VMS

Get your Visitor Management System up and running in minutes!

## Prerequisites

✅ PHP 8.2+  
✅ Composer  
✅ MySQL  
✅ Node.js 18+

## 5-Minute Setup

### 1. Install Dependencies (2 min)

```bash
cd e:\GitProjects\staging\mayfair_VMS
composer install
npm install
copy .env.example .env
php artisan key:generate
```

### 2. Configure Database (1 min)

Edit `.env`:
```env
DB_DATABASE=mayfair_vms
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database and migrate:
```bash
mysql -u root -p -e "CREATE DATABASE mayfair_vms"
php artisan migrate --seed
```

### 3. Build Assets (1 min)

```bash
npm run build
```

### 4. Start Services (1 min)

Open **3 terminal windows**:

**Terminal 1 - Web Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work
```

**Terminal 3 - Dev Assets (optional):**
```bash
npm run dev
```

### 5. Access Application

🌐 **Visitor Registration**: http://localhost:8000  
🔧 **Admin Panel**: http://localhost:8000/admin/visitors

---

## Testing Without APIs

For quick testing without WhatsApp/Google Sheets setup:

1. **OTP Testing**: 
   - OTP is logged to `storage/logs/laravel.log`
   - Check the log file for the 4-digit code

2. **Google Sheets**:
   - System works without it
   - Syncs will be queued but won't execute
   - You can still view all data in admin panel

---

## Next Steps

### Configure WhatsApp OTP (Required for Production)

**Option A - Twilio** (Recommended):
```env
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=your_sid
TWILIO_AUTH_TOKEN=your_token
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

**Option B - Interakt**:
```env
WHATSAPP_PROVIDER=interakt
INTERAKT_API_KEY=your_key
```

📖 [Twilio Setup Guide](https://www.twilio.com/docs/whatsapp)

### Configure Google Sheets (Recommended)

1. Follow [GOOGLE_SHEETS_SETUP.md](GOOGLE_SHEETS_SETUP.md)
2. Update `.env`:
```env
GOOGLE_APPLICATION_CREDENTIALS=storage/app/google/credentials.json
GOOGLE_SHEET_ID=your_spreadsheet_id
GOOGLE_SHEET_NAME=Visitors
```

---

## Test the System

### 1. Register a Visitor

1. Go to http://localhost:8000
2. Select "Customer"
3. Enter:
   - Name: John Doe
   - Mobile: 9876543210
4. Check logs for OTP: `storage/logs/laravel.log`
5. Enter the OTP
6. Select a project
7. Complete registration ✅

### 2. View in Admin

1. Go to http://localhost:8000/admin/visitors
2. See the registered visitor
3. Click "View" for details
4. Check sync status

### 3. Check Google Sheets

If configured, check your Google Sheet for the synced data!

---

## Common Issues

### "Queue not working"
```bash
# Ensure queue worker is running
php artisan queue:work

# Check for failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### "Styles not loading"
```bash
# Rebuild assets
npm run build

# Or use dev mode
npm run dev
```

### "Database connection error"
```bash
# Test connection
php artisan migrate:status

# Check .env credentials
# Ensure MySQL is running
```

---

## Sample Data

The system seeds with:
- **9 Employees** across Sales, Management, Accounts, HR, IT
- **5 Projects** including residential and commercial
- All ready to use!

---

## Production Deployment

### Quick Production Checklist

```bash
# 1. Set production environment
APP_ENV=production
APP_DEBUG=false

# 2. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 3. Build production assets
npm run build

# 4. Set up Supervisor for queue
# See README.md for supervisor config

# 5. Set up SSL certificate (Let's Encrypt recommended)

# 6. Configure backups
```

---

## File Structure Overview

```
mayfair_VMS/
├── app/
│   ├── Livewire/           # Multi-step form component
│   ├── Services/           # WhatsApp & Google Sheets
│   ├── Jobs/               # Queue jobs for syncing
│   └── Models/             # Visitor, Employee, Project
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/            # Sample data
├── resources/
│   └── views/
│       ├── livewire/       # Registration form
│       ├── admin/          # Admin dashboard
│       └── visitor/        # Success page
├── routes/
│   └── web.php            # All routes
├── .env                   # Configuration (create from .env.example)
└── README.md             # Full documentation
```

---

## Support & Documentation

📚 **Full Documentation**: [README.md](README.md)  
🔐 **Google Sheets Setup**: [GOOGLE_SHEETS_SETUP.md](GOOGLE_SHEETS_SETUP.md)  
💬 **WhatsApp Setup**: See README.md Step 3

---

## Features at a Glance

✨ **Multi-step registration**  
📱 **WhatsApp OTP verification**  
👥 **3 visitor types with conditional fields**  
📊 **Auto-sync to Google Sheets**  
🎨 **Beautiful Tailwind UI**  
📱 **Fully responsive**  
⚡ **Queue-based async operations**  
🔒 **Secure and validated**

---

**You're all set! 🎉**

Start by visiting http://localhost:8000 and register your first visitor!
