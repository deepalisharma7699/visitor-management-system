<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is actually an admin
            if (!Auth::guard('admin')->user()->is_admin) {
                Auth::guard('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => 'You do not have admin access.',
                ]);
            }

            return redirect()->intended(route('admin.visitors.index'))
                ->with('success', 'Welcome back, ' . Auth::guard('admin')->user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $employee = Employee::where('email', $request->email)
            ->where('is_admin', true)
            ->first();

        if (!$employee) {
            throw ValidationException::withMessages([
                'email' => 'We could not find an admin account with that email address.',
            ]);
        }

        // Generate a simple reset token (for demonstration)
        $token = Str::random(60);
        
        // In production, you would:
        // 1. Store this token in a password_resets table
        // 2. Send an email with the reset link
        // For now, we'll just show a success message
        
        return back()->with('success', 
            'Password reset instructions have been sent to your email address. (Note: Email functionality not yet configured)'
        );
    }

    /**
     * Show the reset password form
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $employee = Employee::where('email', $request->email)
            ->where('is_admin', true)
            ->first();

        if (!$employee) {
            throw ValidationException::withMessages([
                'email' => 'We could not find an admin account with that email address.',
            ]);
        }

        // Update password
        $employee->password = Hash::make($request->password);
        $employee->save();

        return redirect()->route('admin.login')
            ->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }
}
