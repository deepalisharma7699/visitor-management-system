# WhatsApp Message Templates - Mayfair VMS

This document contains all WhatsApp message templates required for the Visitor Management System, with dummy content examples for both Twilio and Interakt platforms.

---

## 📱 Template Overview

The system uses 3 main message types:
1. **OTP Verification** - Send 4-digit code for verification
2. **Welcome Message** - Confirmation after successful registration
3. **OTP Resend** - When user requests new OTP

### 🌍 International Support

The system supports **international country codes** with country flag emojis using the `intl` PHP extension:
- Automatic country detection from phone numbers
- Country flag display in messages
- Multi-country formatting support
- Not limited to India (+91)

**Supported Countries:** All international country codes (🇮🇳 India, 🇺🇸 USA, 🇬🇧 UK, 🇦🇪 UAE, 🇸🇬 Singapore, 🇦🇺 Australia, etc.)

---

## 🔹 Twilio WhatsApp Templates

Twilio uses **WhatsApp Business API** which requires pre-approved templates for business-initiated messages.

### Template 1: OTP Verification

**Template Name:** `mayfair_vms_otp_verification_yc`  
**Category:** Authentication  
**Language:** English

**Template Content:**
```
Hello {{1}}! 👋

Your Mayfair VMS verification code is: *{{2}}*

⏰ This code will expire in {{3}} minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!
```

**Variables:**
- `{{1}}` - Visitor Name
- `{{2}}` - 4-digit OTP code
- `{{3}}` - Expiration time (5)

**Example with Dummy Data (India 🇮🇳):**
```
Hello Rajesh Kumar! 👋

Your Mayfair VMS verification code is: *4892*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!
```

**Example with Dummy Data (USA 🇺🇸):**
```
Hello John Smith! 👋

Your Mayfair VMS verification code is: *6254*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!
```

**Example with Dummy Data (UAE 🇦🇪):**
```
Hello Ahmed Al Mansoori! 👋

Your Mayfair VMS verification code is: *8173*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!
```

**Code Implementation (with International Support):**
```php
// Get country flag emoji
$countryCode = $this->getCountryCodeFromMobile($mobile); // e.g., 'IN', 'US', 'AE'
$flag = $this->getCountryFlagEmoji($countryCode);

$this->twilioClient->messages->create(
    "whatsapp:{$mobile}",
    [
        'from' => config('services.twilio.whatsapp_from'),
        'contentSid' => 'HX1234567890abcdef1234567890abcdef', // Your template SID
        'contentVariables' => json_encode([
            '1' => $name . ' ' . $flag,
            '2' => $otp,
            '3' => '5'
        ])
    ]
);
```

---

### Template 2: Welcome Message

**Template Name:** `mayfair_welcome_message`  
**Category:** Account Update  
**Language:** English

**Template Content:**
```
Welcome to Mayfair, {{1}}! 🙏

✅ Your registration has been completed successfully!

📋 Registration Details:
• Visitor Type: {{2}}
• Registration ID: #{{3}}
• Date: {{4}}

Thank you for visiting us. Our team will assist you shortly.

Have a great day! 😊

---
Mayfair - Where Excellence Meets Service
```

**Variables:**
- `{{1}}` - Visitor Name
- `{{2}}` - Visitor Type (Guest/Broker/Customer)
- `{{3}}` - Registration ID
- `{{4}}` - Registration Date & Time

**Example with Dummy Data:**
```
Welcome to Mayfair, Priya Sharma! 🙏

✅ Your registration has been completed successfully!

📋 Registration Details:
• Visitor Type: Customer
• Registration ID: #00042
• Date: Dec 18, 2025 2:30 PM

Thank you for visiting us. Our team will assist you shortly.

Have a great day! 😊

---
Mayfair - Where Excellence Meets Service
```

---

### Template 3: OTP Resend Notification

**Template Name:** `mayfair_vms_otp_resend`  
**Category:** Authentication  
**Language:** English

**Template Content:**
```
Hi {{1}}! 👋

As requested, here's your new verification code: *{{2}}*

⏰ Valid for {{3}} minutes.

If you didn't request this, please ignore this message.

🔐 Keep your code secure!
```

**Variables:**
- `{{1}}` - Visitor Name
- `{{2}}` - New OTP code
- `{{3}}` - Expiration time (5)

**Example with Dummy Data:**
```
Hi Amit Patel! 👋

As requested, here's your new verification code: *7251*

⏰ Valid for 5 minutes.

If you didn't request this, please ignore this message.

🔐 Keep your code secure!
```

---

## 🔹 Interakt WhatsApp Templates

Interakt uses a similar template-based system with some platform-specific differences.

### Template 1: OTP Verification

**Template Name:** `otp_verification`  
**Category:** AUTHENTICATION  
**Language:** en

**Template Structure:**
```json
{
  "name": "mayfair_vms_otp_verification_yc",
  "language": "en",
  "category": "AUTHENTICATION",
  "structure": {
    "header": "none",
    "body": "Hello! Your Mayfair VMS verification code is {{1}}. This code will expire in {{2}} minutes. Please do not share this code with anyone.",
    "footer": "Mayfair Visitor Management",
    "buttons": []
  }
}
```

**Body Text:**
```
Hello! Your Mayfair VMS verification code is {{1}}. This code will expire in {{2}} minutes. Please do not share this code with anyone.
```

**Variables:**
- `{{1}}` - 4-digit OTP code
- `{{2}}` - Expiration time (5)

**Example with Dummy Data:**
```
Hello! Your Mayfair VMS verification code is 3847. This code will expire in 5 minutes. Please do not share this code with anyone.

---
Mayfair Visitor Management
```

**Code Implementation (with International Support):**
```php
// Parse international number
$parsedNumber = $this->parseInternationalNumber($mobile);

Http::withHeaders([
    'Authorization' => 'Basic ' . base64_encode(config('services.interakt.api_key')),
    'Content-Type' => 'application/json',
])->post(config('services.interakt.base_url') . '/messages', [
    'countryCode' => $parsedNumber['country_code'], // e.g., '+91', '+1', '+971'
    'phoneNumber' => $parsedNumber['number'],
    'type' => 'Template',
    'template' => [
        'name' => 'mayfair_vms_otp_verification_yc',
        'languageCode' => 'en',
        'bodyValues' => [$otp, '5'],
    ],
]);
```

---

### Template 2: Welcome Message

**Template Name:** `mayfair_vms_welcome_message`  
**Category:** ACCOUNT_UPDATE  
**Language:** en

**Template Structure:**
```json
{
  "name": "mayfair_vms_welcome_message",
  "language": "en",
  "category": "ACCOUNT_UPDATE",
  "structure": {
    "header": {
      "type": "TEXT",
      "text": "Registration Successful! ✅"
    },
    "body": "Welcome to Mayfair, {{1}}! Your registration as {{2}} has been completed. Registration ID: {{3}}. Thank you for visiting us.",
    "footer": "Mayfair - Excellence in Service",
    "buttons": []
  }
}
```

**Body Text:**
```
Welcome to Mayfair, {{1}}! Your registration as {{2}} has been completed. Registration ID: {{3}}. Thank you for visiting us.
```

**Variables:**
- `{{1}}` - Visitor Name
- `{{2}}` - Visitor Type
- `{{3}}` - Registration ID

**Example with Dummy Data:**
```
Registration Successful! ✅

Welcome to Mayfair, Kavita Joshi! Your registration as Broker has been completed. Registration ID: #00087. Thank you for visiting us.

---
Mayfair - Excellence in Service
```

---

### Template 3: Check-out Confirmation

**Template Name:** `checkout_confirmation`  
**Category:** ACCOUNT_UPDATE  
**Language:** en

**Template Structure:**
```json
{
  "name": "checkout_confirmation",
  "language": "en",
  "category": "ACCOUNT_UPDATE",
  "structure": {
    "header": "none",
    "body": "Thank you for visiting Mayfair, {{1}}! You have been checked out at {{2}}. We hope to see you again soon!",
    "footer": "Visit us: www.mayfair.com",
    "buttons": []
  }
}
```

**Example with Dummy Data:**
```
Thank you for visiting Mayfair, Suresh Gupta! You have been checked out at 4:45 PM. We hope to see you again soon!

---
Visit us: www.mayfair.com
```

---

## 🛠️ How to Create Templates

### For Twilio:

1. **Login to Twilio Console**
   - Go to https://console.twilio.com/
   - Navigate to Messaging > Content Template Builder

2. **Create New Template**
   - Click "Create new template"
   - Select "WhatsApp" as channel
   - Choose category (Authentication/Account Update)

3. **Add Template Details**
   - Name: `mayfair_vms_otp_verification_yc`
   - Language: English
   - Add content with variables `{{1}}`, `{{2}}`, etc.

4. **Submit for Approval**
   - Templates need WhatsApp approval (24-48 hours)
   - Once approved, note the Content SID

5. **Use in Code**
   - Replace `contentSid` in the code with your approved template SID

### For Interakt:

1. **Login to Interakt Dashboard**
   - Go to https://app.interakt.shop/
   - Navigate to Templates section

2. **Create New Template**
   - Click "Create Template"
   - Fill template name: `mayfair_vms_otp_verification_yc`
   - Select category: AUTHENTICATION
   - Language: English

3. **Add Template Content**
   - Header (optional): Text or media
   - Body: Your message with `{{1}}`, `{{2}}` variables
   - Footer (optional): Company info
   - Buttons (optional): Quick reply or call-to-action

4. **Submit for Approval**
   - WhatsApp reviews templates (usually 24-48 hours)
   - Check status in dashboard

5. **Use in Code**
   - Use template name in API calls
   - Pass variables in correct order

---

## 📋 Template Variables Reference

### OTP Templates:
| Variable | Description | Example |
|----------|-------------|---------|
| `{{1}}` | Visitor Name | "Rajesh Kumar" |
| `{{2}}` | OTP Code | "4892" |
| `{{3}}` | Expiry Minutes | "5" |

### Welcome Templates:
| Variable | Description | Example |
|----------|-------------|---------|
| `{{1}}` | Visitor Name | "Priya Sharma" |
| `{{2}}` | Visitor Type | "Customer" / "Broker" / "Guest" |
| `{{3}}` | Registration ID | "#00042" |
| `{{4}}` | Date/Time | "Dec 18, 2025 2:30 PM" |

### Checkout Templates:
| Variable | Description | Example |
|----------|-------------|---------|
| `{{1}}` | Visitor Name | "Suresh Gupta" |
| `{{2}}` | Checkout Time | "4:45 PM" |

---

## 💡 Template Best Practices

### WhatsApp Template Guidelines:

1. **Keep it Concise**
   - Max 1024 characters for body text
   - Clear and direct messaging

2. **Use Variables Wisely**
   - Personalize with name
   - Include dynamic data (OTP, ID, time)
   - Don't overuse variables

3. **Follow WhatsApp Policies**
   - No promotional content in authentication templates
   - Be transparent about purpose
   - Include opt-out options where required

4. **Formatting Tips**
   - Use *bold* for important info (OTP codes)
   - Use emojis sparingly for visual appeal
   - Keep paragraphs short

5. **Testing**
   - Test with real numbers
   - Verify variable substitution
   - Check all edge cases

---

## 🔧 Code Integration Examples

### Updated WhatsAppService for Twilio Templates:

```php
protected function sendViaTwilio(string $mobile, string $otp, string $name = ''): bool
{
    // If using approved templates
    if (config('services.twilio.use_templates')) {
        $this->twilioClient->messages->create(
            "whatsapp:{$mobile}",
            [
                'from' => config('services.twilio.whatsapp_from'),
                'contentSid' => config('services.twilio.otp_template_sid'),
                'contentVariables' => json_encode([
                    '1' => $name ?: 'User',
                    '2' => $otp,
                    '3' => '5'
                ])
            ]
        );
    } else {
        // Fallback to plain text (for testing/sandbox)
        $message = "Hello! Your Mayfair VMS verification code is: *{$otp}*\n\n";
        $message .= "This code will expire in 5 minutes.\n\n";
        $message .= "Please do not share this code with anyone.";

        $this->twilioClient->messages->create(
            "whatsapp:{$mobile}",
            [
                'from' => config('services.twilio.whatsapp_from'),
                'body' => $message
            ]
        );
    }

    return true;
}
```

### Updated WhatsAppService for Interakt Templates:

```php
protected function sendViaInterakt(string $mobile, string $otp, string $name = ''): bool
{
    // Parse international number
    $parsedNumber = $this->parseInternationalNumber($mobile);
    
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . base64_encode(config('services.interakt.api_key')),
        'Content-Type' => 'application/json',
    ])->post(config('services.interakt.base_url') . '/messages', [
        'countryCode' => $parsedNumber['country_code'],
        'phoneNumber' => $parsedNumber['number'],
        'type' => 'Template',
        'template' => [
            'name' => 'mayfair_vms_otp_verification_yc',
            'languageCode' => 'en',
            'bodyValues' => [$otp, '5'],
        ],
    ]);

    return $response->successful();
}

/**
 * Parse international phone number
 */
protected function parseInternationalNumber(string $mobile): array
{
    // Remove any non-numeric characters except +
    $mobile = preg_replace('/[^0-9+]/', '', $mobile);
    
    // Ensure it starts with +
    if (!str_starts_with($mobile, '+')) {
        $mobile = '+' . $mobile;
    }
    
    // Extract country code and number
    // Common country codes: +1 (US/CA), +44 (UK), +91 (IN), +971 (UAE), +65 (SG), +61 (AU)
    $countryCodeMap = [
        '+1' => 1,
        '+44' => 2,
        '+91' => 2,
        '+971' => 3,
        '+65' => 2,
        '+61' => 2,
        '+966' => 3,
        '+974' => 3,
        '+86' => 2,
    ];
    
    foreach ($countryCodeMap as $code => $length) {
        if (str_starts_with($mobile, $code)) {
            return [
                'country_code' => $code,
                'number' => substr($mobile, strlen($code)),
                'full' => $mobile,
            ];
        }
    }
    
    // Default fallback
    return [
        'country_code' => '+1',
        'number' => ltrim($mobile, '+'),
        'full' => $mobile,
    ];
}

/**
 * Get country code from mobile number
 */
protected function getCountryCodeFromMobile(string $mobile): string
{
    $mobile = preg_replace('/[^0-9+]/', '', $mobile);
    
    $countryMap = [
        '+1' => 'US',      // USA/Canada
        '+44' => 'GB',     // United Kingdom
        '+91' => 'IN',     // India
        '+971' => 'AE',    // UAE
        '+65' => 'SG',     // Singapore
        '+61' => 'AU',     // Australia
        '+966' => 'SA',    // Saudi Arabia
        '+974' => 'QA',    // Qatar
        '+86' => 'CN',     // China
        '+81' => 'JP',     // Japan
        '+82' => 'KR',     // South Korea
    ];
    
    foreach ($countryMap as $code => $country) {
        if (str_starts_with($mobile, $code)) {
            return $country;
        }
    }
    
    return 'US'; // Default
}

/**
 * Get country flag emoji using intl
 */
protected function getCountryFlagEmoji(string $countryCode): string
{
    if (!extension_loaded('intl')) {
        return '🌍'; // Fallback if intl not available
    }
    
    // Convert country code to regional indicator symbols
    $countryCode = strtoupper($countryCode);
    $flag = '';
    
    for ($i = 0; $i < strlen($countryCode); $i++) {
        $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
    }
    
    return $flag;
}
```

### Environment Configuration:

Add to `.env`:
```env
# Twilio Template Configuration
TWILIO_USE_TEMPLATES=true
TWILIO_OTP_TEMPLATE_SID=HXxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_WELCOME_TEMPLATE_SID=HXyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy

# Interakt Template Names
INTERAKT_OTP_TEMPLATE=mayfair_vms_otp_verification_yc
INTERAKT_WELCOME_TEMPLATE=mayfair_vms_welcome_message

# International Support
ENABLE_COUNTRY_FLAGS=true
DEFAULT_COUNTRY_CODE=IN
```

---

## 🌍 International Country Code Support

### PHP intl Extension

The system uses PHP's `intl` extension to generate country flag emojis. This extension should be enabled in your PHP installation.

**Check if intl is enabled:**
```bash
php -m | grep intl
```

**Enable intl extension:**

For Windows (XAMPP/WAMP):
```ini
# In php.ini, uncomment:
extension=intl
```

For Linux (Ubuntu/Debian):
```bash
sudo apt-get install php-intl
sudo systemctl restart apache2
```

For macOS:
```bash
brew install php@8.2
# intl is usually enabled by default
```

### Supported Country Codes with Flags

| Country | Code | Phone Prefix | Flag Emoji | Example Number |
|---------|------|--------------|------------|----------------|
| India | IN | +91 | 🇮🇳 | +919876543210 |
| United States | US | +1 | 🇺🇸 | +12025551234 |
| United Kingdom | GB | +44 | 🇬🇧 | +447700900123 |
| UAE | AE | +971 | 🇦🇪 | +971501234567 |
| Singapore | SG | +65 | 🇸🇬 | +6591234567 |
| Australia | AU | +61 | 🇦🇺 | +61412345678 |
| Saudi Arabia | SA | +966 | 🇸🇦 | +966501234567 |
| Qatar | QA | +974 | 🇶🇦 | +97433123456 |
| China | CN | +86 | 🇨🇳 | +8613812345678 |
| Japan | JP | +81 | 🇯🇵 | +819012345678 |
| South Korea | KR | +82 | 🇰🇷 | +821012345678 |

### Country Flag Generation Logic

The system converts ISO 3166-1 alpha-2 country codes (like 'IN', 'US', 'GB') to Unicode regional indicator symbols that display as flag emojis:

```php
// Example: 'IN' becomes 🇮🇳
// I = U+0049 → U+1F1EE (Regional Indicator Symbol Letter I)
// N = U+004E → U+1F1F3 (Regional Indicator Symbol Letter N)

function getCountryFlagEmoji(string $countryCode): string
{
    if (!extension_loaded('intl')) {
        return '🌍'; // Fallback globe emoji
    }
    
    $countryCode = strtoupper($countryCode);
    $flag = '';
    
    for ($i = 0; $i < strlen($countryCode); $i++) {
        // Convert each letter to Regional Indicator Symbol
        // A-Z (65-90) → 🇦-🇿 (127462-127487)
        $flag .= mb_chr(ord($countryCode[$i]) + 127397, 'UTF-8');
    }
    
    return $flag;
}
```

### Phone Number Formatting

The system automatically detects country codes from international phone numbers:

```php
// Examples:
parseInternationalNumber('+919876543210');
// Returns: ['country_code' => '+91', 'number' => '9876543210', 'full' => '+919876543210']

parseInternationalNumber('+12025551234');
// Returns: ['country_code' => '+1', 'number' => '2025551234', 'full' => '+12025551234']

parseInternationalNumber('+971501234567');
// Returns: ['country_code' => '+971', 'number' => '501234567', 'full' => '+971501234567']
```

### Adding More Countries

To support additional countries, update the mapping arrays in `WhatsAppService.php`:

```php
// In parseInternationalNumber()
$countryCodeMap = [
    '+1' => 1,      // US/Canada (1 digit code)
    '+44' => 2,     // UK (2 digit code)
    '+91' => 2,     // India
    '+971' => 3,    // UAE (3 digit code)
    '+49' => 2,     // Germany (add new)
    '+33' => 2,     // France (add new)
    '+39' => 2,     // Italy (add new)
    // Add more as needed
];

// In getCountryCodeFromMobile()
$countryMap = [
    '+1' => 'US',
    '+44' => 'GB',
    '+91' => 'IN',
    '+49' => 'DE',  // Germany
    '+33' => 'FR',  // France
    '+39' => 'IT',  // Italy
    // Add more as needed
];
```

---

## 📊 Sample Test Data (International)

### Test Visitor 1 - India 🇮🇳 (Guest/Vendor):
```
Name: Rajesh Kumar
Mobile: +919876543210
Country: India (IN)
Type: Guest > Vendor
OTP: 4892
Company: Tech Solutions Pvt Ltd
Meeting: Priya Sharma (Sales)
```

### Test Visitor 2 - USA 🇺🇸 (Broker):
```
Name: John Smith
Mobile: +12025551234
Country: United States (US)
Type: Broker
OTP: 6254
Company: American Realty Group
Department: Sales
```

### Test Visitor 3 - UAE 🇦🇪 (Customer):
```
Name: Ahmed Al Mansoori
Mobile: +971501234567
Country: United Arab Emirates (AE)
Type: Customer
OTP: 8173
Project: Mayfair Residency
```

### Test Visitor 4 - UK 🇬🇧 (Guest/Interview):
```
Name: James Wilson
Mobile: +447700900123
Country: United Kingdom (GB)
Type: Guest > Interview
OTP: 5392
Meeting: HR Department
```

### Test Visitor 5 - Singapore 🇸🇬 (Broker):
```
Name: Li Wei Chen
Mobile: +6591234567
Country: Singapore (SG)
Type: Broker
OTP: 7416
Company: Asia Pacific Properties
Department: Management
```

### Test Visitor 6 - Australia 🇦🇺 (Customer):
```
Name: Emily Thompson
Mobile: +61412345678
Country: Australia (AU)
Type: Customer
OTP: 2859
Project: Mayfair Heights
```

---

## 🚨 Troubleshooting Templates

### Common Issues:

**Issue 1: Template Not Found**
- Solution: Verify template name exactly matches
- Check template status (must be APPROVED)

**Issue 2: Variable Count Mismatch**
- Solution: Ensure bodyValues array has correct number of variables
- Check template definition vs code

**Issue 3: Template Rejected**
- Solution: Review WhatsApp guidelines
- Avoid promotional language in authentication templates
- Resubmit with corrections

**Issue 4: Formatting Issues**
- Solution: Test with plain text first
- Avoid special characters that break formatting
- Use WhatsApp's preview feature

---

## 📞 Support Resources

- **Twilio Documentation**: https://www.twilio.com/docs/whatsapp
- **Interakt Help**: https://help.interakt.shop/
- **WhatsApp Business Policy**: https://www.whatsapp.com/legal/business-policy/

---

## 📝 Template Approval Checklist

Before submitting templates for approval:

- [ ] Template name follows naming convention
- [ ] Category is appropriate (AUTHENTICATION/ACCOUNT_UPDATE)
- [ ] Language is specified correctly
- [ ] Variables are numbered sequentially ({{1}}, {{2}}, etc.)
- [ ] Content is clear and professional
- [ ] No promotional content in auth templates
- [ ] Character count is within limits
- [ ] Tested with sample data
- [ ] Company/brand name is consistent
- [ ] Contact information included (if required)

---

**All templates ready for implementation! 🎉**

*Last Updated: December 18, 2025*
