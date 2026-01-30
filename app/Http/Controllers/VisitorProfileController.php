<?php

namespace App\Http\Controllers;

use App\Helpers\CountryHelper;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Visitor;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitorProfileController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->middleware('auth:visitor');
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Show visitor dashboard
     */
    public function dashboard()
    {
        $visitor = Auth::guard('visitor')->user();
        return view('visitor.dashboard', compact('visitor'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $visitor = Auth::guard('visitor')->user();
        $employees = Employee::active()->get();
        $projects = Project::active()->get();
        $countries = CountryHelper::getCountries();

        return view('visitor.profile.edit', compact('visitor', 'employees', 'projects', 'countries'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $visitor = Auth::guard('visitor')->user();

        $rules = [
            'name' => 'required|string|min:3|max:255',
            'email' => 'nullable|email|max:255',
        ];

        // Add conditional validation based on visitor type
        if ($visitor->visitor_type === 'guest') {
            if (in_array($request->guest_type, ['vendor', 'contractor'])) {
                $rules['company_name'] = 'required|string|max:255';
            }
            $rules['guest_type'] = 'required|in:friend,family,vendor,contractor,other';
            $rules['whom_to_meet'] = 'required|exists:employees,id';
        } elseif ($visitor->visitor_type === 'broker') {
            $rules['broker_company'] = 'required|string|max:255';
            $rules['meet_department'] = 'required|string|max:255';
        } elseif ($visitor->visitor_type === 'customer') {
            $rules['interested_project'] = 'required|exists:projects,id';
        }

        $validated = $request->validate($rules);

        // Update visitor
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'] ?? $visitor->email,
        ];

        // Add type-specific fields
        if ($visitor->visitor_type === 'guest') {
            $updateData['guest_type'] = $validated['guest_type'];
            $updateData['whom_to_meet'] = $validated['whom_to_meet'];
            $updateData['company_name'] = $validated['company_name'] ?? null;
        } elseif ($visitor->visitor_type === 'broker') {
            $updateData['broker_company'] = $validated['broker_company'];
            $updateData['meet_department'] = $validated['meet_department'];
        } elseif ($visitor->visitor_type === 'customer') {
            $updateData['interested_project'] = $validated['interested_project'];
        }

        $visitor->update($updateData);

        Log::info('Visitor profile updated', [
            'visitor_id' => $visitor->id,
            'updated_fields' => array_keys($updateData)
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show mobile number update form
     */
    public function editMobile()
    {
        $visitor = Auth::guard('visitor')->user();
        $countries = CountryHelper::getCountries();

        return view('visitor.profile.edit-mobile', compact('visitor', 'countries'));
    }

    /**
     * Send OTP to new mobile number
     */
    public function sendMobileOTP(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|min:7|max:15',
            'country_code' => 'required|string',
        ]);

        $visitor = Auth::guard('visitor')->user();
        $fullMobile = $request->country_code . $request->mobile;

        // Check if mobile is already in use by another visitor
        $exists = Visitor::where('mobile', $fullMobile)
            ->where('id', '!=', $visitor->id)
            ->where('otp_verified', true)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This mobile number is already registered.');
        }

        // Generate and save OTP
        $otp = $this->whatsAppService->generateOTP();
        
        session([
            'new_mobile' => $fullMobile,
            'mobile_otp' => $otp,
            'mobile_otp_sent_at' => now(),
        ]);

        // Send OTP
        $sent = $this->whatsAppService->sendOTP(
            $fullMobile,
            $otp,
            $visitor->name,
            $visitor->email ?? ''
        );

        if ($sent) {
            return redirect()->route('visitor.profile.verify-mobile')
                ->with('success', 'OTP sent to new mobile number!');
        }

        return back()->with('error', 'Failed to send OTP. Please try again.');
    }

    /**
     * Show mobile OTP verification form
     */
    public function verifyMobileForm()
    {
        if (!session('new_mobile')) {
            return redirect()->route('visitor.profile.edit-mobile');
        }

        return view('visitor.profile.verify-mobile');
    }

    /**
     * Verify OTP and update mobile
     */
    public function verifyMobileOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        if (!session('new_mobile') || !session('mobile_otp')) {
            return redirect()->route('visitor.profile.edit-mobile')
                ->with('error', 'Session expired. Please try again.');
        }

        if (session('mobile_otp') !== $request->otp) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        // Check if OTP is still valid (5 minutes)
        $otpSentAt = session('mobile_otp_sent_at');
        if (now()->diffInMinutes($otpSentAt) > 5) {
            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        $visitor = Auth::guard('visitor')->user();
        $visitor->update([
            'mobile' => session('new_mobile'),
        ]);

        // Clear session
        session()->forget(['new_mobile', 'mobile_otp', 'mobile_otp_sent_at']);

        Log::info('Visitor mobile updated', ['visitor_id' => $visitor->id]);

        return redirect()->route('visitor.profile.edit')
            ->with('success', 'Mobile number updated successfully!');
    }

    /**
     * Show visitor history
     */
    public function history()
    {
        $visitor = Auth::guard('visitor')->user();
        return view('visitor.profile.history', compact('visitor'));
    }

    /**
     * Checkout visitor
     */
    public function checkout(Request $request)
    {
        $visitor = Auth::guard('visitor')->user();

        if ($visitor->status !== 'checked_in') {
            return back()->with('error', 'You are not currently checked in');
        }

        $visitor->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        Log::info('Visitor checked out via dashboard', [
            'visitor_id' => $visitor->id,
            'name' => $visitor->name,
            'checked_out_at' => now()
        ]);

        return back()->with('success', 'Thank you! You have been checked out successfully');
    }
}
