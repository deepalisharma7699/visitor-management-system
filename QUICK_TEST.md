# 🚀 Quick Start - Testing OTP Without API Keys

## One-Minute Guide

### 1️⃣ Open Application
http://127.0.0.1:8000/register

### 2️⃣ Fill Form
- **Name:** Test User
- **Email:** test@example.com  
- **Mobile:** 9876543210
- **Country:** 🇮🇳 +91

### 3️⃣ Send OTP
Click "Send OTP" button

### 4️⃣ Get OTP from Logs
**PowerShell:**
```powershell
Select-String -Path storage\logs\laravel.log -Pattern '"otp":"' | Select-Object -Last 1
```

**Output will show:**
```
"otp":"4892"
```

### 5️⃣ Enter OTP
Type `4892` in the verification form ✅

---

## 📋 Log Monitoring Commands

### Watch logs live:
```powershell
Get-Content storage\logs\laravel.log -Wait -Tail 10
```

### Find OTP codes:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "otp.*\d{4}"
```

### Show errors:
```powershell
Select-String -Path storage\logs\laravel.log -Pattern "ERROR"
```

---

## ✅ What Works Right Now

- [x] Visitor registration form
- [x] OTP generation
- [x] OTP logging to file
- [x] OTP verification
- [x] Email field (optional)
- [x] International phone codes
- [x] Comprehensive error logging

## 🔧 What to Configure Later

- [ ] WhatsApp API (Twilio/Interakt) - Optional
- [ ] Email SMTP (Gmail/SendGrid) - Optional
- [ ] Google Sheets API - Optional

**For now, everything works with logs!** 🎉

---

## 🆘 Quick Troubleshooting

### Can't find OTP?
```powershell
Select-String storage\logs\laravel.log -Pattern "OTP" | Select -Last 5
```

### Form not submitting?
Check for errors:
```powershell
Select-String storage\logs\laravel.log -Pattern "ERROR|exception" | Select -Last 5
```

### Need to clear logs?
```powershell
Clear-Content storage\logs\laravel.log
```

---

**That's it! You're ready to test!** 🚀
