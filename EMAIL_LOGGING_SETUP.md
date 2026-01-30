# Email & Logging Setup Guide

## 📧 Email Configuration

The system now supports **Email OTP** as a fallback when WhatsApp is not configured.

### Current Setup (Development Mode)

By default, the system uses `MAIL_MAILER=log` which writes emails to `storage/logs/laravel.log` instead of sending them.

**This is perfect for testing without email credentials!**

### How to View Email OTP in Logs

1. **Check the log file:**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   
2. **Or open in VS Code:**
   - Navigate to `storage/logs/laravel.log`
   - Search for "OTP" to find the verification codes

### Example Log Output

```
[2025-12-18 13:45:23] local.INFO: OTP Send Attempt {"mobile":"+919876543210","name":"Rajesh Kumar","email":"rajesh@example.com","provider":"twilio","otp":"4892"}
[2025-12-18 13:45:23] local.INFO: WhatsApp not configured or failed, attempting email fallback
[2025-12-18 13:45:23] local.INFO: Sending OTP via email {"email":"rajesh@example.com","name":"Rajesh Kumar"}
[2025-12-18 13:45:24] local.INFO: Email OTP sent successfully {"email":"rajesh@example.com"}
```

---

## 🔧 Email Providers Setup (Optional)

### Option 1: Gmail (Recommended for Testing)

1. **Enable 2-Factor Authentication** in your Gmail account
2. **Generate App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Create app password for "Mail"
3. **Update `.env`:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-char-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="Mayfair VMS"
   ```

### Option 2: Mailtrap (Best for Development)

1. **Sign up:** https://mailtrap.io/ (Free account)
2. **Get SMTP credentials** from your inbox
3. **Update `.env`:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@mayfair.com
   MAIL_FROM_NAME="Mayfair VMS"
   ```

### Option 3: SendGrid (Production)

1. **Sign up:** https://sendgrid.com/
2. **Create API Key**
3. **Update `.env`:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.sendgrid.net
   MAIL_PORT=587
   MAIL_USERNAME=apikey
   MAIL_PASSWORD=your-sendgrid-api-key
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@mayfair.com
   MAIL_FROM_NAME="Mayfair VMS"
   ```

---

## 📋 Logging System

### Log Levels

The application logs at different levels:

- **INFO**: Normal operations (OTP sent, visitor created, etc.)
- **DEBUG**: Detailed information (configuration checks, parsing, etc.)
- **WARNING**: Important but not critical (WhatsApp not configured, fallback used)
- **ERROR**: Failures and exceptions (OTP send failed, database errors)

### Log Locations

**Main log file:**
```
storage/logs/laravel.log
```

**Log rotation:**
- Laravel automatically rotates logs daily
- Old logs: `laravel-YYYY-MM-DD.log`

### What Gets Logged

#### 1. OTP Operations
```
✅ OTP generation
✅ Send attempts (WhatsApp/Email)
✅ Send results (success/failure)
✅ OTP verification attempts
✅ OTP expiry checks
```

#### 2. Visitor Operations
```
✅ Visitor registration
✅ Form validation
✅ Database inserts
✅ Status changes
```

#### 3. WhatsApp/Email
```
✅ Provider configuration checks
✅ Message formatting
✅ API responses
✅ Errors and exceptions
```

#### 4. Google Sheets Sync
```
✅ Sync job dispatch
✅ Sync success/failure
✅ API errors
```

### Viewing Logs in Real-Time

**PowerShell:**
```powershell
Get-Content storage\logs\laravel.log -Wait -Tail 50
```

**Or use VS Code extension:**
- Install "Log Viewer" extension
- Open `storage/logs/laravel.log`

---

## 🔍 Debugging with Logs

### Scenario 1: OTP Not Received

**Check log for:**
```
"OTP Send Attempt" → See what was sent
"WhatsApp not configured" → Check if WhatsApp API is set up
"attempting email fallback" → See if email was tried
"Email OTP sent successfully" → Confirm email was sent
```

### Scenario 2: Email Not Arriving

**Check log for:**
```
"Sending OTP via email" → Confirm email attempt
"Email OTP sent successfully" → Check if sent
"Email OTP Send Error" → See specific error message
```

**Common issues:**
- Gmail: Need app password, not regular password
- Mailtrap: Check username/password
- Firewall: Port 587/465 may be blocked

### Scenario 3: Form Submission Error

**Check log for:**
```
"SendOTP called" → See form data submitted
"Visitor record created" → Check if saved to DB
"SendOTP exception" → See specific error
```

---

## 🧪 Testing Email OTP

### Step 1: Register a Visitor

1. Go to: http://127.0.0.1:8000/register
2. Select visitor type
3. Enter:
   - Name: Test User
   - Email: test@example.com
   - Mobile: 9876543210
4. Click "Send OTP"

### Step 2: Check Logs

**PowerShell:**
```powershell
cd E:\GitProjects\staging\mayfair_VMS
Select-String -Path storage\logs\laravel.log -Pattern "OTP" | Select-Object -Last 10
```

**Look for:**
```
OTP Send Attempt {"mobile":"+919876543210","name":"Test User","email":"test@example.com","otp":"1234"}
Email OTP sent successfully {"email":"test@example.com"}
```

### Step 3: Find OTP

**Search for the OTP code:**
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "otp.*\d{4}" | Select-Object -Last 5
```

### Step 4: Verify OTP

Enter the OTP from logs into the verification form.

---

## 📊 Log Analysis Commands

### Show all OTP operations today:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "OTP"
```

### Show errors only:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "ERROR"
```

### Show specific visitor's logs:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "visitor_id.*42"
```

### Count errors:
```powershell
(Select-String -Path storage\logs\laravel.log -Pattern "ERROR").Count
```

---

## 🎯 Production Recommendations

### 1. Change Mail Driver
```env
MAIL_MAILER=smtp  # Change from 'log' to actual SMTP
```

### 2. Configure Real SMTP
Use Gmail, SendGrid, or Mailgun

### 3. Adjust Log Level
```env
LOG_LEVEL=warning  # Change from 'debug' to reduce log size
```

### 4. Set up Log Rotation
Logs automatically rotate daily, clean old ones:
```powershell
Remove-Item storage\logs\laravel-*.log -Exclude laravel.log
```

### 5. Monitor Logs
Use tools like:
- Papertrail
- Loggly
- Sentry (for errors)

---

## ✅ Quick Test Checklist

- [ ] Log file created: `storage/logs/laravel.log`
- [ ] Can see OTP in logs
- [ ] Email field shows in registration form
- [ ] OTP sent log message appears
- [ ] Can verify OTP from logs
- [ ] Errors are logged with details
- [ ] Can tail logs in real-time

---

## 🆘 Troubleshooting

### Issue: No logs appearing

**Solution:**
```powershell
# Ensure logs directory is writable
New-Item -ItemType Directory -Force -Path storage/logs
# Check if log file exists
Test-Path storage/logs/laravel.log
```

### Issue: Email not in logs

**Solution:**
```powershell
# Search for email in entire log
Select-String -Path storage\logs\laravel.log -Pattern "email"
```

### Issue: Can't find OTP code

**Solution:**
```powershell
# Show last 20 lines with OTP
Select-String -Path storage\logs\laravel.log -Pattern "otp.*:" | Select-Object -Last 20
```

---

**All set! Your logging and email system is ready for testing!** 🎉

*Last Updated: December 18, 2025*
