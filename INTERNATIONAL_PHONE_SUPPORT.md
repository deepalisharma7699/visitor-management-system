# International Phone Number Support - Mayfair VMS

This document explains how the Visitor Management System handles international phone numbers with country-specific formatting and flag emojis.

---

## 🌍 Overview

The system now supports **international phone numbers** from multiple countries with:
- ✅ Country-specific dial codes (+91, +1, +971, etc.)
- ✅ Country flag emojis (🇮🇳, 🇺🇸, 🇦🇪, etc.) using PHP `intl` extension
- ✅ Dynamic phone number formatting
- ✅ Country-aware WhatsApp messaging
- ✅ User-friendly country selector in registration form

---

## 📋 Supported Countries

| Country | Code | Dial Code | Flag | Max Digits |
|---------|------|-----------|------|------------|
| India | IN | +91 | 🇮🇳 | 10 |
| United States | US | +1 | 🇺🇸 | 10 |
| United Kingdom | GB | +44 | 🇬🇧 | 10 |
| UAE | AE | +971 | 🇦🇪 | 9 |
| Singapore | SG | +65 | 🇸🇬 | 8 |
| Australia | AU | +61 | 🇦🇺 | 9 |
| Saudi Arabia | SA | +966 | 🇸🇦 | 9 |
| Qatar | QA | +974 | 🇶🇦 | 8 |
| China | CN | +86 | 🇨🇳 | 11 |
| Japan | JP | +81 | 🇯🇵 | 10 |
| South Korea | KR | +82 | 🇰🇷 | 10 |
| Canada | CA | +1 | 🇨🇦 | 10 |
| Germany | DE | +49 | 🇩🇪 | 11 |
| France | FR | +33 | 🇫🇷 | 9 |
| Italy | IT | +39 | 🇮🇹 | 10 |
| Spain | ES | +34 | 🇪🇸 | 9 |

---

## 🛠️ Technical Implementation

### 1. CountryHelper Class

Located at `app/Helpers/CountryHelper.php`, this helper provides:

```php
// Get all supported countries
$countries = CountryHelper::getCountries();

// Get country by dial code
$country = CountryHelper::getCountryByDialCode('+91');
// Returns: ['code' => 'IN', 'name' => 'India', 'dial_code' => '+91', 'flag' => '🇮🇳', 'max_length' => 10]

// Get flag emoji from country code
$flag = CountryHelper::getFlagEmoji('IN');
// Returns: 🇮🇳

// Parse phone number
$info = CountryHelper::parsePhoneNumber('+919876543210');
// Returns: [
//     'country' => [...],
//     'dial_code' => '+91',
//     'number' => '9876543210',
//     'full' => '+919876543210',
//     'flag' => '🇮🇳'
// ]
```

### 2. Updated WhatsAppService

The service now automatically detects country and adds flag emojis to messages:

```php
// Old message (India-specific):
"Your Mayfair VMS verification code is: *1234*"

// New message (International with flag):
"Hello Rajesh Kumar 🇮🇳!

Your Mayfair VMS verification code is: *1234*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!"
```

**Key Methods:**
- `parseInternationalNumber()` - Extracts country code and number
- Integration with `CountryHelper` for flag emojis
- Supports both Twilio and Interakt platforms

### 3. Frontend Implementation

**Livewire Component (`VisitorRegistration.php`):**
```php
public $countryCode = '+91'; // Default
public $mobile = '';
public $countries = [];

public function mount()
{
    $this->countries = CountryHelper::getCountries();
}
```

**Blade Template:**
```html
<!-- Country Selector with Flags -->
<select wire:model.live="countryCode">
    @foreach($countries as $country)
        <option value="{{ $country['dial_code'] }}">
            {{ $country['flag'] }} {{ $country['dial_code'] }}
        </option>
    @endforeach
</select>

<!-- Mobile Number Input -->
<input type="tel" wire:model="mobile" placeholder="Enter mobile number" maxlength="15">

<!-- Display Selected Country -->
<p>Selected: {{ $countryCode }} ({{ collect($countries)->firstWhere('dial_code', $countryCode)['name'] ?? 'Unknown' }})</p>
```

---

## 📱 User Experience

### Registration Flow:

1. **Step 1**: Select Visitor Type (Guest/Broker/Customer)

2. **Step 2**: Enter Details
   - Name input field
   - **Country selector dropdown** with flags (🇮🇳, 🇺🇸, 🇦🇪, etc.)
   - Mobile number input field
   - Live display of selected country

3. **Step 3**: OTP Verification
   - Display: "We've sent a 4-digit code to your WhatsApp: **+971 501234567**"
   - WhatsApp message includes country flag emoji

4. **Step 4**: Complete Registration

---

## 🔧 PHP intl Extension

### Why intl Extension?

The `intl` extension enables:
- Proper Unicode handling for flag emojis
- International character support
- Locale-aware formatting

### Installation

**Check if enabled:**
```bash
php -m | grep intl
```

**Windows (XAMPP/WAMP):**
```ini
# Edit php.ini
extension=intl
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install php-intl
sudo systemctl restart apache2
```

**macOS:**
```bash
brew install php@8.2
# intl is usually included by default
```

**Docker:**
```dockerfile
RUN docker-php-ext-install intl
```

### Fallback Without intl

If `intl` is not available, the system uses hardcoded flag emojis from the CountryHelper array:

```php
if (!extension_loaded('intl')) {
    // Uses predefined flags: 🇮🇳, 🇺🇸, 🇦🇪, etc.
    $country = CountryHelper::getCountryByCode('IN');
    return $country ? $country['flag'] : '🌍';
}
```

---

## 📊 Example Use Cases

### Example 1: Indian Visitor
```
Input:
- Country: 🇮🇳 +91
- Mobile: 9876543210

Stored in DB: +919876543210

WhatsApp Message:
"Hello Rajesh Kumar 🇮🇳!

Your Mayfair VMS verification code is: *4892*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!"
```

### Example 2: UAE Visitor
```
Input:
- Country: 🇦🇪 +971
- Mobile: 501234567

Stored in DB: +971501234567

WhatsApp Message:
"Hello Ahmed Al Mansoori 🇦🇪!

Your Mayfair VMS verification code is: *8173*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!"
```

### Example 3: US Visitor
```
Input:
- Country: 🇺🇸 +1
- Mobile: 2025551234

Stored in DB: +12025551234

WhatsApp Message:
"Hello John Smith 🇺🇸!

Your Mayfair VMS verification code is: *6254*

⏰ This code will expire in 5 minutes.

🔒 For security reasons, please do not share this code with anyone.

Thank you for visiting Mayfair!"
```

---

## 🎨 Frontend Enhancement Ideas

### Future Improvements:

1. **Country Search** - Add search functionality in dropdown
   ```html
   <input type="text" placeholder="Search country..." />
   ```

2. **Auto-detect Country** - Use IP geolocation
   ```javascript
   fetch('https://ipapi.co/json/')
     .then(data => selectCountryByCode(data.country_code));
   ```

3. **Recent Countries** - Show recently used countries at top

4. **Flag Display** - Larger flag icons for better visibility

5. **Phone Formatting** - Real-time formatting as user types
   ```
   US: (202) 555-1234
   UK: 07700 900123
   IN: 98765 43210
   ```

---

## 🧪 Testing

### Test Different Countries:

```php
// India
$mobile = '+919876543210';
$info = CountryHelper::parsePhoneNumber($mobile);
// flag: 🇮🇳, country: India

// UAE
$mobile = '+971501234567';
$info = CountryHelper::parsePhoneNumber($mobile);
// flag: 🇦🇪, country: UAE

// USA
$mobile = '+12025551234';
$info = CountryHelper::parsePhoneNumber($mobile);
// flag: 🇺🇸, country: United States
```

### Test OTP Sending:

```php
$whatsAppService = app(WhatsAppService::class);

// Send OTP with name
$whatsAppService->sendOTP('+919876543210', '4892', 'Rajesh Kumar');
// Message includes: "Hello Rajesh Kumar 🇮🇳!"

// Send OTP to UAE number
$whatsAppService->sendOTP('+971501234567', '8173', 'Ahmed Al Mansoori');
// Message includes: "Hello Ahmed Al Mansoori 🇦🇪!"
```

---

## 🔐 Security Considerations

1. **Phone Number Validation**
   - Validates format before saving
   - Ensures dial code matches country

2. **OTP Security**
   - Same security for all countries
   - 5-minute expiration
   - 4-digit random code

3. **Data Storage**
   - Stores full international format: `+919876543210`
   - Consistent across all countries

---

## 📝 Database Schema

The `mobile` field in `visitors` table now stores international format:

```sql
-- Example data:
+919876543210     -- India
+12025551234      -- USA
+971501234567     -- UAE
+447700900123     -- UK
+6591234567       -- Singapore
```

No database migration needed - the field already supports `varchar(20)`.

---

## 🌐 Adding New Countries

To add support for more countries:

1. **Update CountryHelper.php:**
```php
public static function getCountries(): array
{
    return [
        // ... existing countries ...
        [
            'code' => 'NL',
            'name' => 'Netherlands',
            'dial_code' => '+31',
            'flag' => '🇳🇱',
            'max_length' => 9,
        ],
    ];
}
```

2. **Update WhatsAppService parsing:**
```php
$countryCodeMap = [
    // ... existing codes ...
    '+31' => 2,    // Netherlands
];

$countryMap = [
    // ... existing codes ...
    '+31' => 'NL', // Netherlands
];
```

3. **Test with sample numbers:**
```php
$mobile = '+31612345678'; // Netherlands
```

---

## 📚 References

- **PHP intl Extension**: https://www.php.net/manual/en/book.intl.php
- **Unicode Flag Emojis**: https://en.wikipedia.org/wiki/Regional_indicator_symbol
- **WhatsApp Business API**: https://developers.facebook.com/docs/whatsapp
- **Country Codes (ISO 3166)**: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2

---

## ✅ Checklist

Before deploying international phone support:

- [x] Install PHP `intl` extension
- [x] Update `.env` with default country code
- [x] Test OTP sending to different countries
- [x] Verify flag emojis display correctly
- [x] Test country selector in registration form
- [x] Validate phone number formats
- [x] Check WhatsApp API compatibility
- [x] Test with Twilio and Interakt
- [x] Update documentation
- [x] Train admin users on international support

---

**International Phone Support Active! 🌍🎉**

*Last Updated: December 18, 2025*
