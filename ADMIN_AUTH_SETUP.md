# Admin Authentication Setup Guide

## Overview
Admin authentication has been successfully added to the Mayfair VMS system. Admins can now login with email and password to access the admin panel.

## Setup Steps

### 1. Run the Migration
Add the password, remember_token, and is_admin fields to the employees table:

```bash
php artisan migrate
```

### 2. Create Admin Users
Seed the database with default admin users:

```bash
php artisan db:seed --class=AdminSeeder
```

**Default Admin Credentials:**
- Email: `admin@mayfair.com`
- Password: `password`

**Test Manager Account:**
- Email: `manager@mayfair.com`
- Password: `password`

⚠️ **IMPORTANT:** Change these passwords in production!

### 3. Access Admin Panel
- Visit: `/admin/login`
- Or click "Admin Panel" link from the visitor registration page

## Features

### Security
- ✅ Separate `admin` authentication guard
- ✅ Password hashing with bcrypt
- ✅ Remember me functionality
- ✅ Session regeneration on login
- ✅ CSRF protection
- ✅ Admin-only access verification (is_admin flag)

### Protected Routes
All admin routes now require authentication:
- `/admin/visitors` - Visitor list
- `/admin/visitors/{id}` - Visitor details
- `/admin/sync/status` - Sync status
- `/admin/sync/manual` - Manual sync

### User Experience
- Clean login form with Mayfair branding
- Flash messages for success/error states
- Logout button in admin panel header
- Shows admin name and department in header
- Redirects to intended page after login

## Creating New Admins

### Option 1: Using Tinker
```bash
php artisan tinker
```

```php
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

Employee::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => Hash::make('your-password'),
    'department' => 'Management',
    'designation' => 'Administrator',
    'is_active' => true,
    'is_admin' => true,
]);
```

### Option 2: Direct Database
Update an existing employee to be an admin:

```sql
UPDATE employees 
SET password = '$2y$12$YOUR_HASHED_PASSWORD', 
    is_admin = 1 
WHERE email = 'employee@mayfair.com';
```

## Files Modified/Created

### New Files:
- `database/migrations/2026_01_29_000001_add_password_to_employees_table.php`
- `app/Http/Controllers/Auth/AdminAuthController.php`
- `resources/views/admin/auth/login.blade.php`
- `database/seeders/AdminSeeder.php`

### Modified Files:
- `app/Models/Employee.php` - Added Authenticatable traits
- `config/auth.php` - Added admin guard and employee provider
- `routes/web.php` - Added admin auth routes and middleware
- `resources/views/admin/layout.blade.php` - Added logout button
- `resources/views/components/layouts/app.blade.php` - Updated admin link

## Testing

1. **Test Login:**
   - Go to `/admin/login`
   - Enter: `admin@mayfair.com` / `password`
   - Should redirect to visitors list

2. **Test Protected Routes:**
   - Try accessing `/admin/visitors` without login
   - Should redirect to login page

3. **Test Logout:**
   - Click "Logout" button in admin panel
   - Should redirect to login page

4. **Test Non-Admin Employee:**
   - Create an employee with `is_admin = false`
   - Try to login - should be rejected

## Security Recommendations

1. **Change Default Passwords:**
   ```bash
   php artisan tinker
   ```
   ```php
   $admin = Employee::where('email', 'admin@mayfair.com')->first();
   $admin->password = Hash::make('new-secure-password');
   $admin->save();
   ```

2. **Enable Rate Limiting:**
   Add throttle middleware to login route in future updates

3. **Add Password Reset:**
   Implement password reset functionality for admins

4. **Two-Factor Authentication:**
   Consider adding 2FA for enhanced security

## Troubleshooting

### Login Not Working
- Clear cache: `php artisan cache:clear`
- Clear config: `php artisan config:clear`
- Check migrations ran: `php artisan migrate:status`

### Redirecting to Wrong Page
- Check `config/auth.php` for correct guard settings
- Clear sessions: `php artisan session:clear`

### Password Not Hashing
- Ensure `'password' => 'hashed'` in Employee model casts
- Use `Hash::make()` when creating passwords
