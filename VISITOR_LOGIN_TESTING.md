# Visitor Login & Profile System - Testing Guide

## Quick Test Steps

### 1. Test Visitor Login

**Prerequisites:** Have a registered and verified visitor in the database

**Steps:**
1. Navigate to `/visitor/login`
2. Enter the visitor's email or mobile number
3. Click "Send OTP"
4. Check logs for OTP code: `storage/logs/laravel.log`
5. Enter the OTP on the verification page
6. Should redirect to `/visitor/dashboard`

**Expected Results:**
- ✅ OTP sent successfully
- ✅ OTP verified correctly
- ✅ Visitor logged in
- ✅ Dashboard displays visitor information

### 2. Test Profile Edit

**Steps:**
1. Login as a visitor
2. Click "Edit Profile" from dashboard
3. Update name or email
4. Update type-specific fields (e.g., guest type, company)
5. Click "Update Profile"

**Expected Results:**
- ✅ Form displays current information
- ✅ Validation works (try submitting empty required fields)
- ✅ Profile updates successfully
- ✅ Success message displayed

### 3. Test Mobile Number Update

**Steps:**
1. Login as a visitor
2. Go to "Edit Profile"
3. Click "click here" link next to mobile number
4. Enter new mobile number with country code
5. Click "Send OTP"
6. Check logs for OTP
7. Enter OTP and verify

**Expected Results:**
- ✅ Can't use mobile number already registered by another visitor
- ✅ OTP sent to new number
- ✅ OTP verification works
- ✅ Mobile number updated in database
- ✅ Can login with new mobile number

### 4. Test Visit History

**Steps:**
1. Login as a visitor
2. Click "Visit History" from dashboard
3. View visit details

**Expected Results:**
- ✅ Shows registration date/time
- ✅ Shows check-in time (if checked in)
- ✅ Shows check-out time (if checked out)
- ✅ Displays visit duration
- ✅ Shows sync status

### 5. Test Logout

**Steps:**
1. Login as a visitor
2. Click "Logout" button from dashboard
3. Try accessing `/visitor/dashboard`

**Expected Results:**
- ✅ Logged out successfully
- ✅ Redirected to login page
- ✅ Protected routes redirect to login

### 6. Test Security

**Test OTP Expiration:**
1. Request OTP
2. Wait 6 minutes
3. Try to verify with the OTP

**Expected:** OTP expired error

**Test Invalid OTP:**
1. Request OTP
2. Enter wrong code

**Expected:** Invalid OTP error

**Test Unverified Visitor:**
1. Create a visitor without OTP verification
2. Try to login with their details

**Expected:** No account found error

**Test Protected Routes (Not Logged In):**
1. Logout or open in incognito
2. Try to access:
   - `/visitor/dashboard`
   - `/visitor/profile`
   - `/visitor/history`

**Expected:** Redirected to login page

**Test Guest Routes (Already Logged In):**
1. Login as a visitor
2. Try to access `/visitor/login`

**Expected:** Redirected to dashboard

## Manual Database Checks

### Check Visitor Authentication

```sql
-- Check if visitor exists and is verified
SELECT id, name, mobile, email, otp_verified, status 
FROM visitors 
WHERE mobile LIKE '%YOUR_NUMBER%' OR email = 'YOUR_EMAIL';

-- Check OTP details
SELECT id, name, otp_code, otp_sent_at, otp_verified 
FROM visitors 
WHERE id = YOUR_VISITOR_ID;
```

### Verify Profile Updates

```sql
-- Before update
SELECT * FROM visitors WHERE id = YOUR_VISITOR_ID;

-- After update (check if changes persisted)
SELECT * FROM visitors WHERE id = YOUR_VISITOR_ID;
```

## Common Issues & Solutions

### Issue: OTP not being sent

**Check:**
1. WhatsApp service configuration in `.env`
2. Email configuration if using email OTP
3. Check logs: `tail -f storage/logs/laravel.log`

**Solution:**
```bash
# Check logs
cat storage/logs/laravel.log | grep OTP

# Verify environment variables
php artisan config:clear
php artisan cache:clear
```

### Issue: Login redirects back to login page

**Check:**
1. Session driver configuration
2. Visitor guard configuration
3. Check if visitor is verified

**Solution:**
```bash
# Clear sessions
php artisan session:clear

# Check session configuration
cat config/session.php
```

### Issue: Profile not updating

**Check:**
1. Form validation errors
2. Database connection
3. Mass assignment protection in Visitor model

**Solution:**
- Check if all fields are in `$fillable` array
- Check browser console for JavaScript errors
- Check network tab for form submission

### Issue: "Undefined guard [visitor]" error

**Check:**
1. `config/auth.php` has visitor guard
2. Configuration cached

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```

## Browser Testing Checklist

### Desktop Testing
- [ ] Chrome - Latest
- [ ] Firefox - Latest
- [ ] Edge - Latest
- [ ] Safari - Latest (Mac)

### Mobile Testing
- [ ] Mobile Chrome (Android)
- [ ] Mobile Safari (iOS)
- [ ] Mobile Firefox

### Responsive Testing
- [ ] Mobile (320px - 480px)
- [ ] Tablet (768px - 1024px)
- [ ] Desktop (1024px+)

## Performance Testing

### Load Time
- [ ] Login page loads in < 2s
- [ ] Dashboard loads in < 2s
- [ ] Profile edit loads in < 2s

### OTP Delivery
- [ ] OTP delivered within 10 seconds
- [ ] Email OTP delivered within 30 seconds

## Accessibility Testing

- [ ] Keyboard navigation works
- [ ] Screen reader compatible
- [ ] Color contrast meets WCAG standards
- [ ] Forms have proper labels
- [ ] Error messages are clear

## Integration Testing

### With Existing Registration
- [ ] After registration, visitor can immediately login
- [ ] Registration OTP and Login OTP work independently
- [ ] Registered visitor appears in admin panel

### With Admin Panel
- [ ] Admin can see all visitors including newly logged-in ones
- [ ] Check-in/check-out affects visitor dashboard
- [ ] Sync status updates reflect in visitor history

## Automation Test Commands

```bash
# Run Laravel tests (if written)
php artisan test

# Check code style
php artisan pint

# Check for common issues
php artisan route:list | grep visitor
php artisan config:show auth
```

## Test Users

Create test visitors for different scenarios:

```php
// In tinker or seeder
php artisan tinker

// Create verified guest visitor
$guest = Visitor::create([
    'visitor_type' => 'guest',
    'name' => 'Test Guest',
    'mobile' => '+919999999999',
    'email' => 'testguest@example.com',
    'guest_type' => 'friend',
    'whom_to_meet' => 1,
    'otp_verified' => true,
    'status' => 'verified',
]);

// Create verified broker visitor
$broker = Visitor::create([
    'visitor_type' => 'broker',
    'name' => 'Test Broker',
    'mobile' => '+919999999998',
    'email' => 'testbroker@example.com',
    'broker_company' => 'Test Realty',
    'meet_department' => 'Sales',
    'otp_verified' => true,
    'status' => 'verified',
]);

// Create verified customer visitor
$customer = Visitor::create([
    'visitor_type' => 'customer',
    'name' => 'Test Customer',
    'mobile' => '+919999999997',
    'email' => 'testcustomer@example.com',
    'interested_project' => 1,
    'otp_verified' => true,
    'status' => 'verified',
]);
```

## Success Criteria

All tests pass when:
- ✅ Login works with email and mobile
- ✅ OTP verification works correctly
- ✅ Dashboard displays accurate information
- ✅ Profile updates successfully
- ✅ Mobile number can be changed with OTP
- ✅ Visit history shows correct data
- ✅ Logout works properly
- ✅ Security measures are effective
- ✅ No console errors
- ✅ Responsive on all devices
- ✅ Accessible to all users

## Reporting Issues

When reporting an issue, include:
1. Steps to reproduce
2. Expected behavior
3. Actual behavior
4. Screenshots/screen recordings
5. Browser and OS information
6. Relevant log entries
7. Database state (if applicable)

## Next Steps After Testing

Once all tests pass:
1. Document any edge cases found
2. Update user documentation
3. Train staff on new features
4. Monitor production logs after deployment
5. Gather user feedback
