# Visitor Login & Profile Management System

## Overview

The Visitor Management System now includes a complete authentication and profile management system for registered visitors. Visitors can:

- Login using their registered email or mobile number with OTP verification
- View their dashboard with visit information
- Edit their profile information
- Update their mobile number (with OTP verification)
- View their visit history

## Features

### 1. Visitor Login with OTP

**Login Flow:**
1. Visitor enters their registered email or mobile number
2. System sends a 4-digit OTP to their WhatsApp (or email if available)
3. Visitor enters the OTP to complete login
4. Redirected to their dashboard

**Routes:**
- `/visitor/login` - Login form
- `/visitor/login/send-otp` - Send OTP
- `/visitor/login/verify` - Verify OTP and login

**Security:**
- OTP expires after 5 minutes
- Only verified visitors can login
- Session-based authentication with `visitor` guard

### 2. Visitor Dashboard

After login, visitors can access their personalized dashboard showing:
- Quick stats (visitor type, status, registration date)
- Profile information
- Quick actions (edit profile, view history)
- Current visit details

**Route:** `/visitor/dashboard`

### 3. Profile Management

Visitors can edit their profile information including:
- Name
- Email
- Type-specific fields (guest type, company, projects, etc.)

**Routes:**
- `/visitor/profile` - View and edit profile
- PUT `/visitor/profile` - Update profile

**Mobile Number Update:**
Since mobile number is used for login, updating it requires OTP verification:
1. Enter new mobile number
2. Receive OTP on new number
3. Verify OTP to complete update

**Routes:**
- `/visitor/profile/mobile` - Change mobile form
- `/visitor/profile/mobile/send-otp` - Send OTP to new number
- `/visitor/profile/mobile/verify` - Verify and update

### 4. Visit History

Visitors can view their visit records including:
- Registration date and time
- Check-in/check-out times
- Visit duration
- Sync status

**Route:** `/visitor/history`

### 5. Logout

Visitors can securely logout from their account.

**Route:** POST `/visitor/logout`

## Technical Implementation

### Authentication Configuration

**File:** `config/auth.php`

Added visitor authentication guard:
```php
'guards' => [
    'visitor' => [
        'driver' => 'session',
        'provider' => 'visitors',
    ],
],

'providers' => [
    'visitors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Visitor::class,
    ],
],
```

### Visitor Model Update

**File:** `app/Models/Visitor.php`

Changed from `Model` to `Authenticatable`:
```php
use Illuminate\Foundation\Auth\User as Authenticatable;

class Visitor extends Authenticatable
{
    // ... existing code
}
```

### Controllers

#### VisitorAuthController
**File:** `app/Http/Controllers/Auth/VisitorAuthController.php`

Handles:
- Login form display
- OTP generation and sending for login
- OTP verification and authentication
- Logout

#### VisitorProfileController
**File:** `app/Http/Controllers/VisitorProfileController.php`

Handles:
- Dashboard display
- Profile editing
- Mobile number update with OTP verification
- Visit history

### Middleware

**File:** `app/Http/Middleware/VisitorAuth.php`

Custom middleware to protect visitor routes.

**Registration:** `bootstrap/app.php`
```php
$middleware->alias([
    'auth.visitor' => \App\Http\Middleware\VisitorAuth::class,
]);
```

### Routes

**File:** `routes/web.php`

All visitor authentication routes are prefixed with `/visitor`:

**Public Routes (Guest Only):**
- GET `/visitor/login`
- POST `/visitor/login/send-otp`
- GET `/visitor/login/verify`
- POST `/visitor/login/verify`

**Protected Routes (Authenticated Visitors):**
- GET `/visitor/dashboard`
- GET `/visitor/profile`
- PUT `/visitor/profile`
- GET `/visitor/profile/mobile`
- POST `/visitor/profile/mobile/send-otp`
- GET `/visitor/profile/mobile/verify`
- POST `/visitor/profile/mobile/verify`
- GET `/visitor/history`
- POST `/visitor/logout`

### Views

All views use Tailwind CSS and follow the existing design system.

**Authentication Views:**
- `resources/views/visitor/auth/login.blade.php`
- `resources/views/visitor/auth/verify-otp.blade.php`

**Dashboard & Profile Views:**
- `resources/views/visitor/dashboard.blade.php`
- `resources/views/visitor/profile/edit.blade.php`
- `resources/views/visitor/profile/edit-mobile.blade.php`
- `resources/views/visitor/profile/verify-mobile.blade.php`
- `resources/views/visitor/profile/history.blade.php`

## Usage Guide

### For Visitors

**First Time:**
1. Register as a visitor (existing flow)
2. After successful registration, click "Login to Your Account" button
3. Login using your registered email or mobile number

**Returning Visitors:**
1. Go to `/visitor/login`
2. Enter your email or mobile number
3. Receive OTP and verify
4. Access your dashboard

**Profile Management:**
1. Login to your account
2. Click "Edit Profile" on dashboard
3. Update your information
4. Click "Update Profile" to save

**Change Mobile Number:**
1. Go to "Edit Profile"
2. Click "click here" link next to mobile number
3. Enter new mobile number
4. Verify OTP sent to new number
5. Mobile number updated

### For Developers

**Check if Visitor is Authenticated:**
```php
if (Auth::guard('visitor')->check()) {
    // Visitor is authenticated
}
```

**Get Authenticated Visitor:**
```php
$visitor = Auth::guard('visitor')->user();
```

**In Blade Templates:**
```blade
@auth('visitor')
    <!-- Show for authenticated visitors -->
    <p>Welcome, {{ Auth::guard('visitor')->user()->name }}</p>
@endauth

@guest('visitor')
    <!-- Show for guests -->
    <a href="{{ route('visitor.login') }}">Login</a>
@endguest
```

**Protect Routes:**
```php
Route::middleware('auth:visitor')->group(function () {
    // Protected visitor routes
});
```

## Security Features

1. **OTP Expiration:** OTPs expire after 5 minutes
2. **Session Security:** Sessions are properly invalidated on logout
3. **CSRF Protection:** All forms include CSRF tokens
4. **Guest Middleware:** Login routes are only accessible to non-authenticated visitors
5. **Auth Middleware:** Profile routes require authentication
6. **Mobile Verification:** Mobile number changes require OTP verification

## Database Considerations

No new migrations required. The system uses existing `visitors` table fields:
- `otp_code` - Stores OTP for verification
- `otp_sent_at` - Tracks OTP send time for expiration
- `otp_verified` - Ensures only verified visitors can login

## Testing Checklist

- [ ] Visitor can login with registered email
- [ ] Visitor can login with registered mobile number
- [ ] OTP is sent via WhatsApp/Email
- [ ] Invalid OTP shows error message
- [ ] Expired OTP shows error message
- [ ] Unverified visitors cannot login
- [ ] Dashboard displays correct information
- [ ] Profile can be updated successfully
- [ ] Mobile number update requires OTP verification
- [ ] Visit history shows accurate data
- [ ] Logout works correctly
- [ ] Protected routes redirect to login
- [ ] Already logged-in visitors can't access login page

## Future Enhancements

Potential improvements:
1. Password-based login option
2. Remember me functionality
3. Multiple visit records per visitor
4. Visit booking/scheduling
5. Digital visitor badges
6. QR code check-in
7. Push notifications
8. Two-factor authentication

## Support

For issues or questions:
1. Check application logs: `storage/logs/laravel.log`
2. Verify OTP service configuration
3. Check database for visitor records
4. Ensure session driver is properly configured

## Changelog

**Version 1.0 - December 25, 2025**
- Initial release of visitor login system
- Profile management functionality
- Mobile number update with OTP
- Visit history display
- Dashboard for authenticated visitors
