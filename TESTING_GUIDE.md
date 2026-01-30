# Testing OTP with Logging

## 🧪 Quick Test

Your system is now configured to:
1. ✅ Try WhatsApp OTP first (if configured)
2. ✅ Fallback to Email OTP automatically
3. ✅ Log everything to `storage/logs/laravel.log`

## 📝 How to Test

### Step 1: Open the Application
Visit: http://127.0.0.1:8000/register

### Step 2: Fill the Form
```
Visitor Type: Guest
Name: Test User
Email: test@example.com
Mobile: 9876543210
Country: 🇮🇳 +91
```

### Step 3: Click "Send OTP"

### Step 4: Check Logs for OTP
**Option A - PowerShell (Real-time):**
```powershell
Get-Content storage\logs\laravel.log -Wait -Tail 20
```

**Option B - PowerShell (Search for OTP):**
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "OTP" | Select-Object -Last 10
```

**Option C - Open in VS Code:**
1. Open `storage/logs/laravel.log`
2. Press `Ctrl+F` and search for "otp"
3. Look for lines like: `"otp":"1234"`

### Step 5: Find Your OTP Code

Look for this in the logs:
```json
{
  "mobile": "+919876543210",
  "name": "Test User",
  "email": "test@example.com",
  "provider": "twilio",
  "otp": "4892"  ← YOUR OTP CODE HERE
}
```

### Step 6: Enter OTP
Copy the 4-digit code from logs and enter it in the form!

---

## 📊 What You'll See in Logs

### Successful Email OTP Flow:
```
[INFO] OTP Send Attempt {"mobile":"+919876543210","name":"Test User","email":"test@example.com","otp":"4892"}
[INFO] WhatsApp not configured or failed, attempting email fallback
[INFO] Sending OTP via email {"email":"test@example.com","name":"Test User"}
[INFO] Email OTP sent successfully {"email":"test@example.com"}
```

### If Email is Not Configured (Development Mode):
```
[INFO] OTP Send Attempt {"mobile":"+919876543210","otp":"4892"}
[WARNING] No WhatsApp or Email configured, OTP logged only
```

This is **PERFECT** for development! You can still test by getting the OTP from logs.

---

## 🎯 Email Preview in Logs

When `MAIL_MAILER=log`, emails are written to logs. Look for:

```
From: noreply@mayfair.com
To: test@example.com
Subject: Your Mayfair VMS Verification Code

Hello Test User,

Your Verification Code: 4892

⏰ Valid for 5 minutes
🔒 Do not share this code
```

---

## 🔍 Debugging Commands

### View last 50 log lines:
```powershell
Get-Content storage\logs\laravel.log -Tail 50
```

### Search for errors:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "ERROR"
```

### Find specific visitor's OTP:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "Test User.*otp"
```

### Watch logs live while testing:
```powershell
Get-Content storage\logs\laravel.log -Wait
```

---

## ✅ Verification

After sending OTP, you should see:

- [x] "OTP Send Attempt" log entry
- [x] Either "Twilio/Interakt" or "Email fallback" message
- [x] "OTP sent successfully" confirmation
- [x] 4-digit OTP code visible in logs
- [x] No ERROR entries (if configured correctly)

---

## 🎉 Ready to Test!

Your application now has:
- ✅ Email OTP support
- ✅ Comprehensive logging
- ✅ Automatic fallback system
- ✅ Development-friendly testing

**No API keys needed for testing!** Just use the logs to get your OTP codes.

---

*Pro Tip: Keep the log file open while testing to see everything in real-time!*
