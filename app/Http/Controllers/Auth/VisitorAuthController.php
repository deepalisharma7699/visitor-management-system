<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitorAuthController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::guard('visitor')->check()) {
            return redirect()->route('visitor.dashboard');
        }

        return view('visitor.auth.login');
    }

    /**
     * Send OTP for login
     */
    public function sendLoginOTP(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        // Find visitor by email or mobile
        $visitor = Visitor::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                  ->orWhere('mobile', 'like', '%' . $identifier);
        })
        ->where('otp_verified', true)
        ->first();

        if (!$visitor) {
            return back()->with('error', 'No verified account found with this email or mobile number.');
        }

        // Generate and save OTP
        $otp = $this->whatsAppService->generateOTP();
        $visitor->update([
            'otp_code' => $otp,
            'otp_sent_at' => now(),
        ]);

        Log::info('Login OTP generated', [
            'visitor_id' => $visitor->id,
            'identifier' => $identifier
        ]);

        // Send OTP via WhatsApp or Email
        $sent = $this->whatsAppService->sendOTP(
            $visitor->mobile,
            $otp,
            $visitor->name,
            $visitor->email ?? ''
        );

        if ($sent) {
            session([
                'login_visitor_id' => $visitor->id,
                'login_identifier' => $identifier,
            ]);

            $message = $visitor->email && filter_var($identifier, FILTER_VALIDATE_EMAIL)
                ? 'OTP sent to your email!'
                : 'OTP sent to your WhatsApp number!';

            return redirect()->route('visitor.login.verify')->with('success', $message);
        }

        return back()->with('error', 'Failed to send OTP. Please try again.');
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOTP()
    {
        if (!session('login_visitor_id')) {
            return redirect()->route('visitor.login');
        }

        return view('visitor.auth.verify-otp');
    }

    /**
     * Verify OTP and login
     */
    public function verifyLoginOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $visitorId = session('login_visitor_id');
        if (!$visitorId) {
            return redirect()->route('visitor.login')
                ->with('error', 'Session expired. Please try again.');
        }

        $visitor = Visitor::find($visitorId);

        if (!$visitor) {
            return redirect()->route('visitor.login')
                ->with('error', 'Invalid session.');
        }

        if ($visitor->otp_code !== $request->otp) {
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        if (!$visitor->isOtpValid()) {
            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // Login the visitor
        Auth::guard('visitor')->login($visitor, true);

        // Clear session data
        session()->forget(['login_visitor_id', 'login_identifier']);

        Log::info('Visitor logged in successfully', ['visitor_id' => $visitor->id]);

        return redirect()->route('visitor.dashboard')
            ->with('success', 'Welcome back, ' . $visitor->name . '!');
    }

    /**
     * Logout visitor
     */
    public function logout(Request $request)
    {
        Auth::guard('visitor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('visitor.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
