# Auto-Checkout System Documentation

## Overview
Automatic checkout system for visitors who forget to check out, ensuring clean data and preventing overnight check-ins.

## Configuration

### 1. Dubai Timezone Setup ✓
**File:** `config/app.php`

```php
'timezone' => env('APP_TIMEZONE', 'Asia/Dubai'),
```

All timestamps (`created_at`, `updated_at`, `checked_in_at`, `checked_out_at`) are now in Dubai time.

## Date Helper Functions

**File:** `app/Helpers/DateHelper.php`

### Available Functions:

#### 1. `format_date_dubai($date, $format = 'd-M-Y h:i A')`
Formats dates in the required format: **29-Jan-2026 05:00 PM**

```php
// Example usage
echo format_date_dubai($visitor->checked_in_at);
// Output: 29-Jan-2026 05:00 PM

echo format_date_dubai(now(), 'd-M-Y');
// Output: 29-Jan-2026
```

#### 2. `dubai_now()`
Get current date/time in Dubai timezone

```php
$currentTime = dubai_now();
```

#### 3. `dubai_today()`
Get today's date (midnight) in Dubai timezone

```php
$today = dubai_today();
```

#### 4. `is_previous_day($date)`
Check if a date is from a previous day

```php
if (is_previous_day($visitor->checked_in_at)) {
    // This visit is from a previous day
}
```

## Visitor Model Methods

### Scopes:

#### `expiredVisits()`
Get all visitors with expired visits (checked in previous days, not checked out)

```php
$expired = Visitor::expiredVisits()->get();
```

#### `checkedIn()`
Get currently checked-in visitors

```php
$active = Visitor::checkedIn()->get();
```

### Instance Methods:

#### `isExpired()`
Check if a single visitor's visit is expired

```php
if ($visitor->isExpired()) {
    // Visitor checked in previous day
}
```

#### `autoCheckout()`
Automatically checkout a visitor (sets time to 11:59 PM of check-in day)

```php
$visitor->autoCheckout();
```

### Computed Attributes:

#### `visit_duration`
Get duration in hours

```php
echo $visitor->visit_duration; // 4.5 (hours)
```

#### `formatted_check_in`
Dubai-formatted check-in time

```php
echo $visitor->formatted_check_in; // 29-Jan-2026 05:00 PM
```

#### `formatted_check_out`
Dubai-formatted check-out time

```php
echo $visitor->formatted_check_out; // 29-Jan-2026 09:30 PM
```

## Auto-Checkout Command

**File:** `app/Console/Commands/AutoCheckoutVisitors.php`

### Manual Execution:

#### Preview (Dry Run):
```bash
php artisan visitors:auto-checkout --dry-run
```

Shows which visitors would be checked out without making changes.

#### Execute:
```bash
php artisan visitors:auto-checkout
```

Checks out all expired visitors.

### Command Features:
- ✅ Displays table of expired visitors
- ✅ Shows check-in time and hours elapsed
- ✅ Confirmation prompt before execution
- ✅ Progress bar for bulk operations
- ✅ Success/failure reporting
- ✅ Sets checkout time to 11:59 PM of check-in day

### Sample Output:
```
🔍 Searching for expired visits (Dubai Time: 29-Jan-2026 12:01 AM)...

Found 3 visitor(s) with expired visits:

+----+-------------+-------------+----------+------------------------+----------------+
| ID | Name        | Mobile      | Type     | Checked In             | Hours Elapsed  |
+----+-------------+-------------+----------+------------------------+----------------+
| 15 | John Doe    | 0501234567  | Customer | 28-Jan-2026 02:30 PM   | 33.5h          |
| 22 | Jane Smith  | 0509876543  | Broker   | 28-Jan-2026 10:00 AM   | 38.0h          |
| 31 | Bob Wilson  | 0507654321  | Guest    | 27-Jan-2026 04:00 PM   | 56.0h          |
+----+-------------+-------------+----------+------------------------+----------------+

Do you want to auto-checkout these visitors? (yes/no) [yes]:
> yes

⏳ Processing auto-checkout...
███████████████████████████████████ 100%

✓ Successfully auto-checked-out 3 visitor(s)

Note: Visitors were checked out at 11:59 PM of their check-in day.
```

## Automated Scheduling

**File:** `routes/console.php`

The command runs automatically every day at **12:01 AM Dubai time**.

```php
Schedule::command('visitors:auto-checkout')
    ->dailyAt('00:01')
    ->timezone('Asia/Dubai')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Auto-checkout completed successfully');
    })
    ->onFailure(function () {
        \Log::error('Auto-checkout failed');
    });
```

### Enable Task Scheduler:

Add to your crontab (Linux/Mac):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or run as Windows Task Scheduler:
```powershell
php artisan schedule:run
```

For development, run:
```bash
php artisan schedule:work
```

## Logic Explanation

### Q: What is the best logic to detect expired visits?

**Answer: Date Comparison (Recommended)**

We compare **dates** rather than hours because:
- ✅ Clear business rule: "Previous day = expired"
- ✅ Works regardless of check-in time
- ✅ Timezone-aware (Dubai time)
- ✅ Handles overnight visits correctly

**Implementation:**
```php
public function scopeExpiredVisits($query)
{
    $today = \Carbon\Carbon::today('Asia/Dubai');
    
    return $query->where('status', 'checked_in')
                ->whereNull('checked_out_at')
                ->whereDate('checked_in_at', '<', $today);
}
```

**Example:**
- Visitor checks in: 28-Jan-2026 11:59 PM
- Current time: 29-Jan-2026 12:01 AM
- Result: **Expired** (previous day)

### Alternative: Hours-based Logic

If you prefer checking hours elapsed:

```php
public function scopeExpiredVisits($query)
{
    $cutoffTime = dubai_now()->subHours(24);
    
    return $query->where('status', 'checked_in')
                ->whereNull('checked_out_at')
                ->where('checked_in_at', '<', $cutoffTime);
}
```

**We recommend date-based** for cleaner daily cycles.

## Status Values

After auto-checkout, visitor status becomes:
- `checked_out_auto` - Automatically checked out by system

Regular checkout:
- `checked_out` - Manually checked out

This helps differentiate in reports.

## Testing

### Test the helpers:
```php
// In tinker: php artisan tinker
use App\Models\Visitor;

// Test date formatting
echo format_date_dubai(now());
// 29-Jan-2026 05:00 PM

// Get expired visits
$expired = Visitor::expiredVisits()->get();

// Test auto-checkout
$visitor = Visitor::find(1);
$visitor->autoCheckout();
```

### Test the command:
```bash
# Dry run first
php artisan visitors:auto-checkout --dry-run

# Then execute
php artisan visitors:auto-checkout
```

## Production Checklist

- [ ] Set `.env` timezone: `APP_TIMEZONE=Asia/Dubai`
- [ ] Run `composer dump-autoload` to load helpers
- [ ] Test command: `php artisan visitors:auto-checkout --dry-run`
- [ ] Configure cron/task scheduler
- [ ] Monitor logs for auto-checkout results
- [ ] Update visitor views to use `formatted_check_in` / `formatted_check_out`

## Blade Template Usage

```blade
<!-- Display formatted dates in views -->
<p>Checked In: {{ $visitor->formatted_check_in }}</p>
<p>Checked Out: {{ $visitor->formatted_check_out }}</p>

<!-- Or use helper directly -->
<p>Date: {{ format_date_dubai($visitor->created_at) }}</p>

<!-- Check if expired -->
@if($visitor->isExpired())
    <span class="badge badge-danger">Expired Visit</span>
@endif

<!-- Show duration -->
<p>Duration: {{ round($visitor->visit_duration, 1) }} hours</p>
```

## Troubleshooting

### Helpers not found
```bash
composer dump-autoload
```

### Scheduler not running
Check if cron is configured or run manually:
```bash
php artisan schedule:work
```

### Wrong timezone
Verify in `.env`:
```env
APP_TIMEZONE=Asia/Dubai
```

Clear config cache:
```bash
php artisan config:clear
```

## Summary

**Best Practices Implemented:**
1. ✅ Dubai timezone configured globally
2. ✅ Date-based expiry detection (cleaner than hours)
3. ✅ Helper functions for consistent formatting
4. ✅ Automated daily cleanup at midnight
5. ✅ Manual command with dry-run option
6. ✅ Distinguishable status (`checked_out_auto`)
7. ✅ Comprehensive logging and error handling
