<?php

namespace App\Livewire;

use App\Helpers\CountryHelper;
use App\Jobs\SyncVisitorToGoogleSheets;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Visitor;
use App\Services\WhatsAppService;
use Livewire\Component;

class VisitorRegistration extends Component
{
    // Step tracking
    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Visitor Type Selection
    public $visitorType = '';
    public $returningVisitorMode = false;
    public $identifier = ''; // For returning visitor email/mobile

    // Step 2: Basic Info
    public $name = '';
    public $mobile = '';
    public $email = ''; // Added for email OTP fallback
    public $countryCode = '+91'; // Default to India
    public $countryName = 'India';
    public $countryFlag = '🇮🇳';

    // Step 3: OTP Verification
    public $otp = '';
    public $otpSent = false;
    public $visitorId = null;
    public $isReturningVisitor = false;
    public $hasActiveCheckIn = false;

    // Step 4: Additional Details (conditional based on visitor type)
    
    // Common (additional email for final details if needed)
    public $additionalEmail = '';

    // Guest specific
    public $guestType = '';
    public $purposeOfVisit = ''; // For 'other' guest type
    public $companyName = '';
    public $whomToMeet = '';
    public $employeeSearch = ''; // For searching employees
    public $accompanyingCount = 0;

    // Broker specific
    public $brokerCompany = '';
    public $meetDepartment = '';

    // Customer specific
    public $interestedProject = '';

    // Available data for dropdowns
    public $employees = [];
    public $projects = [];
    public $filteredEmployees = [];
    public $countries = [];

    protected $whatsAppService;

    public function boot(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function mount()
    {
        $this->employees = Employee::active()->get();
        $this->projects = Project::active()->get();
        $this->countries = CountryHelper::getCountries();
    }

    // Step 1: Select Visitor Type
    public function selectVisitorType($type)
    {
        $this->visitorType = $type;
        $this->returningVisitorMode = false;
        $this->currentStep = 2;
    }

    // Activate Returning Visitor Mode
    public function selectReturningVisitor()
    {
        $this->returningVisitorMode = true;
        $this->currentStep = 2;
    }

    // Step 2: Submit Basic Info and Send OTP
    public function sendOTP()
    {
        \Log::info('SendOTP called', [
            'name' => $this->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'countryCode' => $this->countryCode
        ]);

        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'mobile' => 'required|string|min:7|max:15',
            'email' => 'required|email',
            'countryCode' => 'required|string',
        ]);

        try {
            // Format full international number
            $fullMobile = $this->countryCode . $this->mobile;

            // Check if visitor already exists by email or mobile
            $existingVisitor = Visitor::where(function ($query) use ($fullMobile) {
                $query->where('mobile', $fullMobile);
                if ($this->email) {
                    $query->orWhere('email', $this->email);
                }
            })
            ->where('otp_verified', true)
            ->first();

            if ($existingVisitor) {
                // Returning visitor - use existing record
                $visitor = $existingVisitor;
                $this->isReturningVisitor = true;
                
                // Update OTP for verification
                $visitor->update([
                    'otp_code' => $this->whatsAppService->generateOTP(),
                    'otp_sent_at' => now(),
                ]);
                
                \Log::info('Returning visitor found', [
                    'visitor_id' => $visitor->id,
                    'name' => $visitor->name
                ]);
            } else {
                // New visitor - create record
                $visitor = Visitor::create([
                    'visitor_type' => $this->visitorType,
                    'name' => $this->name,
                    'mobile' => $fullMobile,
                    'email' => $this->email,
                    'otp_code' => $this->whatsAppService->generateOTP(),
                    'otp_sent_at' => now(),
                    'status' => 'pending',
                ]);
                
                $this->isReturningVisitor = false;
                \Log::info('New visitor record created', ['visitor_id' => $visitor->id]);
            }

            $this->visitorId = $visitor->id;

            // Send OTP via WhatsApp or Email with visitor name
            $sent = $this->whatsAppService->sendOTP($fullMobile, $visitor->otp_code, $this->name, $this->email ?? '');

            if ($sent) {
                \Log::info('OTP sent successfully', ['visitor_id' => $visitor->id]);
                $this->otpSent = true;
                $this->currentStep = 3;
                $message = $this->email ? 'OTP sent to your email!' : 'OTP sent to your WhatsApp number!';
                session()->flash('success', $message . ' Check logs if not received.');
            } else {
                \Log::error('OTP send failed', ['visitor_id' => $visitor->id]);
                session()->flash('error', 'Failed to send OTP. Check application logs for details.');
            }
        } catch (\Exception $e) {
            \Log::error('SendOTP exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Send OTP for Returning Visitor
    public function sendReturningVisitorOTP()
    {
        \Log::info('Returning visitor OTP requested', ['identifier' => $this->identifier]);

        $this->validate([
            'identifier' => 'required|string',
        ]);

        try {
            // Find visitor by email or mobile
            $visitor = Visitor::where(function ($query) {
                $query->where('email', $this->identifier)
                      ->orWhere('mobile', 'like', '%' . $this->identifier);
            })
            ->where('otp_verified', true)
            ->first();

            if (!$visitor) {
                session()->flash('error', 'No verified account found with this email or mobile number.');
                return;
            }

            $this->isReturningVisitor = true;
            $this->visitorId = $visitor->id;

            // Generate and save OTP
            $otp = $this->whatsAppService->generateOTP();
            $visitor->update([
                'otp_code' => $otp,
                'otp_sent_at' => now(),
            ]);

            \Log::info('Returning visitor OTP generated', ['visitor_id' => $visitor->id]);

            // Send OTP
            $sent = $this->whatsAppService->sendOTP(
                $visitor->mobile,
                $otp,
                $visitor->name,
                $visitor->email ?? ''
            );

            if ($sent) {
                $this->otpSent = true;
                $this->currentStep = 3;
                session()->flash('success', 'OTP sent! Please check your WhatsApp or email.');
            } else {
                session()->flash('error', 'Failed to send OTP. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Returning visitor OTP exception', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Step 3: Verify OTP
    public function verifyOTP()
    {
        \Log::info('VerifyOTP called', [
            'visitor_id' => $this->visitorId,
            'otp_input' => $this->otp
        ]);

        $this->validate([
            'otp' => 'required|digits:4',
        ]);

        $visitor = Visitor::find($this->visitorId);

        if (!$visitor) {
            \Log::error('Visitor not found', ['visitor_id' => $this->visitorId]);
            session()->flash('error', 'Visitor record not found.');
            return;
        }

        \Log::debug('OTP verification data', [
            'stored_otp' => $visitor->otp_code,
            'input_otp' => $this->otp,
            'otp_sent_at' => $visitor->otp_sent_at,
            'is_valid' => $visitor->isOtpValid()
        ]);

        if (!$visitor->isOtpValid()) {
            session()->flash('error', 'OTP has expired. Please request a new one.');
            return;
        }

        if ($this->whatsAppService->verifyOTP($visitor->otp_code, $this->otp)) {
            $visitor->update([
                'otp_verified' => true,
                'verified_at' => now(),
                'status' => 'verified',
            ]);

            // Auto-fill form with existing data for returning visitors
            if ($this->isReturningVisitor) {
                // Check if visitor is currently checked in
                if ($visitor->isCurrentlyCheckedIn()) {
                    $this->hasActiveCheckIn = true;
                    $this->currentStep = 4; // Show blocked message
                    
                    \Log::warning('Visitor attempted new check-in while already checked in', [
                        'visitor_id' => $visitor->id,
                        'checked_in_at' => $visitor->checked_in_at,
                        'name' => $visitor->name
                    ]);
                    
                    session()->flash('error', 'You are currently checked in. Please check out before starting a new visit.');
                    return;
                }
                
                // Load all basic information
                $this->visitorType = $visitor->visitor_type;
                $this->name = $visitor->name;
                $this->email = $visitor->email;
                
                // Extract mobile without country code
                if (preg_match('/^(\+\d{1,4})(\d+)$/', $visitor->mobile, $matches)) {
                    $this->countryCode = $matches[1];
                    $this->mobile = $matches[2];
                } else {
                    $this->mobile = str_replace($this->countryCode, '', $visitor->mobile);
                }
                
                // Auto-fill type-specific fields
                if ($visitor->visitor_type === 'guest') {
                    $this->guestType = $visitor->guest_type ?? '';
                    $this->purposeOfVisit = $visitor->purpose_of_visit ?? '';
                    $this->companyName = $visitor->company_name ?? '';
                    $this->whomToMeet = $visitor->whom_to_meet ?? '';
                    $this->accompanyingCount = $visitor->accompanying_count ?? 0;
                } elseif ($visitor->visitor_type === 'broker') {
                    $this->brokerCompany = $visitor->broker_company ?? '';
                    $this->meetDepartment = $visitor->meet_department ?? '';
                    if ($this->meetDepartment) {
                        $this->updatedMeetDepartment();
                    }
                } elseif ($visitor->visitor_type === 'customer') {
                    $this->interestedProject = $visitor->interested_project ?? '';
                }
                
                \Log::info('Form auto-filled for returning visitor', ['visitor_id' => $visitor->id]);
            }

            $this->currentStep = 4;
            $message = $this->isReturningVisitor 
                ? 'OTP verified! Your previous details have been pre-filled. Please review and update as needed.' 
                : 'OTP verified successfully!';
            session()->flash('success', $message);
        } else {
            session()->flash('error', 'Invalid OTP. Please try again.');
        }
    }

    // Resend OTP
    public function resendOTP()
    {
        \Log::info('ResendOTP called', ['visitor_id' => $this->visitorId]);

        $visitor = Visitor::find($this->visitorId);

        if (!$visitor) {
            \Log::error('Visitor not found for resend', ['visitor_id' => $this->visitorId]);
            return;
        }

        $newOtp = $this->whatsAppService->generateOTP();
        \Log::info('New OTP generated', ['visitor_id' => $visitor->id, 'otp' => $newOtp]);
        
        $visitor->update([
            'otp_code' => $newOtp,
            'otp_sent_at' => now(),
        ]);

        $sent = $this->whatsAppService->sendOTP($visitor->mobile, $newOtp, $visitor->name, $visitor->email ?? '');
        
        if ($sent) {
            \Log::info('OTP resent successfully', ['visitor_id' => $visitor->id]);
            session()->flash('success', 'New OTP sent successfully!');
        } else {
            \Log::error('OTP resend failed', ['visitor_id' => $visitor->id]);
            session()->flash('error', 'Failed to resend OTP. Check logs.');
        }
    }

    // Step 4: Submit Additional Details
    public function submitDetails()
    {
        // Prevent submission if visitor has active check-in
        if ($this->hasActiveCheckIn) {
            session()->flash('error', 'You are currently checked in. Please check out before starting a new visit.');
            return;
        }
        
        $this->validateStep4();

        $visitor = Visitor::find($this->visitorId);

        if (!$visitor) {
            session()->flash('error', 'Visitor record not found.');
            return;
        }

        $updateData = $this->getUpdateData();
        $visitor->update($updateData);
        
        \Log::info($this->isReturningVisitor ? 'Returning visitor updated' : 'New visitor completed', [
            'visitor_id' => $visitor->id,
            'is_returning' => $this->isReturningVisitor
        ]);

        // Dispatch job to sync with Google Sheets
        SyncVisitorToGoogleSheets::dispatch($visitor);

        // Send welcome message via WhatsApp and Email
        $this->whatsAppService->sendWelcomeMessage(
            $this->mobile,
            $this->name,
            $this->visitorType,
            $this->email // Pass email for welcome email
        );

        // Mark as checked in
        $visitor->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        session()->flash('success', 'Registration completed successfully!');
        
        // Redirect to success page
        return redirect()->route('visitor.success', ['visitor' => $visitor->id]);
    }

    protected function validateStep4()
    {
        $rules = [
            'email' => 'required|email',
        ];

        if ($this->visitorType === 'guest') {
            $rules['guestType'] = 'required|in:vendor,contractor,interview,other';
            $rules['whomToMeet'] = 'required';
            
            // Require purpose_of_visit when guest type is 'other'
            if ($this->guestType === 'other') {
                $rules['purposeOfVisit'] = 'required|string|max:500';
            }

            if (in_array($this->guestType, ['vendor', 'contractor'])) {
                $rules['companyName'] = 'required|string|max:255';
            }
            
            $rules['accompanyingCount'] = 'nullable|integer|min:0|max:50';
        } elseif ($this->visitorType === 'broker') {
            $rules['brokerCompany'] = 'required|string|max:255';
            $rules['meetDepartment'] = 'required|in:Sales,Management,Accounts';
        } elseif ($this->visitorType === 'customer') {
            $rules['interestedProject'] = 'required';
        }

        $this->validate($rules);
    }

    protected function getUpdateData()
    {
        $data = ['email' => $this->email];

        if ($this->visitorType === 'guest') {
            $data['guest_type'] = $this->guestType;
            $data['whom_to_meet'] = $this->whomToMeet;
            
            // Add purpose of visit for 'other' guest type
            if ($this->guestType === 'other') {
                $data['purpose_of_visit'] = $this->purposeOfVisit;
            }
            
            if (in_array($this->guestType, ['vendor', 'contractor'])) {
                $data['company_name'] = $this->companyName;
            }

            $data['accompanying_count'] = $this->accompanyingCount ?? 0;
        } elseif ($this->visitorType === 'broker') {
            $data['broker_company'] = $this->brokerCompany;
            $data['meet_department'] = $this->meetDepartment;
        } elseif ($this->visitorType === 'customer') {
            $data['interested_project'] = $this->interestedProject;
        }

        return $data;
    }

    // Update filtered employees based on department
    public function updatedMeetDepartment()
    {
        if ($this->meetDepartment) {
            $this->filteredEmployees = $this->employees
                ->where('department', $this->meetDepartment)
                ->values();
        }
    }

    // Navigation methods
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            
            // Reset returning visitor mode when going back to step 1
            if ($this->currentStep === 1) {
                $this->returningVisitorMode = false;
                $this->identifier = '';
                $this->isReturningVisitor = false;
            }
        }
    }

    public function render()
    {
        return view('livewire.visitor-registration');
    }
}
