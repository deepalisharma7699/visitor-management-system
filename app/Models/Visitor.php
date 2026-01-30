<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Visitor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'visitor_type',
        'name',
        'mobile',
        'email',
        'guest_type',
        'purpose_of_visit',
        'company_name',
        'whom_to_meet',
        'accompanying_persons',
        'accompanying_count',
        'broker_company',
        'meet_department',
        'interested_project',
        'otp_code',
        'otp_sent_at',
        'otp_verified',
        'verified_at',
        'synced_to_sheets',
        'synced_at',
        'status',
        'checked_in_at',
        'checked_out_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($visitor) {
            if (empty($visitor->registration_id)) {
                $visitor->registration_id = self::generateRegistrationId();
            }
        });
    }

    /**
     * Generate unique registration ID in format: MF-YYYYMMDD-XXXX
     */
    public static function generateRegistrationId(): string
    {
        $dateCode = now()->format('Ymd'); // YYYYMMDD format
        $prefix = "MF-{$dateCode}-";

        // Get the count of visitors created today
        $todayCount = self::whereDate('created_at', now()->toDateString())->count();
        
        // Increment for the next sequence
        $sequence = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $sequence;
    }

    protected $casts = [
        'accompanying_persons' => 'array',
        'otp_sent_at' => 'datetime',
        'verified_at' => 'datetime',
        'synced_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'otp_verified' => 'boolean',
        'synced_to_sheets' => 'boolean',
        'accompanying_count' => 'integer',
    ];

    /**
     * Get the employee this visitor is meeting (for guests)
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'whom_to_meet');
    }

    /**
     * Get the project the customer is interested in
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'interested_project');
    }

    /**
     * Check if visitor is currently checked in (active visit)
     */
    public function isCurrentlyCheckedIn(): bool
    {
        return $this->status === 'checked_in' && is_null($this->checked_out_at);
    }

    /**
     * Check if OTP is still valid (5 minutes)
     */
    public function isOtpValid(): bool
    {
        if (!$this->otp_sent_at) {
            return false;
        }

        return $this->otp_sent_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Check if visitor needs company name (vendor or contractor)
     */
    public function needsCompanyName(): bool
    {
        return $this->visitor_type === 'guest' && 
               in_array($this->guest_type, ['vendor', 'contractor']);
    }

    /**
     * Get formatted data for Google Sheets
     */
    public function toSheetRow(): array
    {
        $row = [
            $this->id,
            $this->created_at->format('Y-m-d H:i:s'),
            ucfirst($this->visitor_type),
            $this->name,
            $this->mobile,
            $this->email ?? 'N/A',
        ];

        // Add type-specific fields
        switch ($this->visitor_type) {
            case 'guest':
                $row[] = ucfirst(str_replace('_', ' ', $this->guest_type ?? 'N/A'));
                $row[] = $this->company_name ?? 'N/A';
                $row[] = $this->whom_to_meet ?? 'N/A';
                $row[] = $this->accompanying_count;
                $row[] = $this->status;
                break;
            
            case 'broker':
                $row[] = $this->broker_company ?? 'N/A';
                $row[] = $this->meet_department ?? 'N/A';
                $row[] = $this->status;
                break;
            
            case 'customer':
                $row[] = $this->interested_project ?? 'N/A';
                $row[] = $this->status;
                break;
        }

        return $row;
    }

    /**
     * Scope to get verified visitors
     */
    public function scopeVerified($query)
    {
        return $query->where('otp_verified', true);
    }

    /**
     * Scope to get unsynced visitors
     */
    public function scopeUnsynced($query)
    {
        return $query->where('synced_to_sheets', false)
                    ->where('otp_verified', true);
    }

    /**
     * Scope to get currently checked-in visitors
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in')
                    ->whereNull('checked_out_at');
    }

    /**
     * Scope to get visitors from previous days (expired visits)
     * These are visitors who checked in but never checked out
     */
    public function scopeExpiredVisits($query)
    {
        $today = \Carbon\Carbon::today('Asia/Dubai');
        
        return $query->where('status', 'checked_in')
                    ->whereNull('checked_out_at')
                    ->whereDate('checked_in_at', '<', $today);
    }

    /**
     * Check if this visit is expired (from previous day)
     */
    public function isExpired(): bool
    {
        if (!$this->checked_in_at || $this->checked_out_at) {
            return false;
        }

        $checkedInDate = $this->checked_in_at->timezone('Asia/Dubai')->startOfDay();
        $today = \Carbon\Carbon::today('Asia/Dubai');

        return $checkedInDate->lt($today);
    }

    /**
     * Auto checkout this visitor
     */
    public function autoCheckout(): bool
    {
        if ($this->status !== 'checked_in' || $this->checked_out_at) {
            return false;
        }

        // Set checkout time to end of their check-in day (11:59 PM)
        $checkoutTime = $this->checked_in_at
            ->timezone('Asia/Dubai')
            ->endOfDay();

        $this->checked_out_at = $checkoutTime;
        $this->status = 'checked_out_auto';
        
        return $this->save();
    }

    /**
     * Get visit duration in hours
     */
    public function getVisitDurationAttribute(): ?float
    {
        if (!$this->checked_in_at) {
            return null;
        }

        $endTime = $this->checked_out_at ?? dubai_now();
        
        return $this->checked_in_at->diffInHours($endTime, true);
    }

    /**
     * Get formatted check-in time (Dubai timezone)
     */
    public function getFormattedCheckInAttribute(): string
    {
        return format_date_dubai($this->checked_in_at);
    }

    /**
     * Get formatted check-out time (Dubai timezone)
     */
    public function getFormattedCheckOutAttribute(): string
    {
        return format_date_dubai($this->checked_out_at);
    }
}
