<?php

use App\Http\Controllers\Auth\VisitorAuthController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitorProfileController;
use App\Http\Controllers\Api\EmployeeSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('visitor.register');
});

// Default login route (redirects to admin login)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// API Routes
Route::get('/api/employees/search', [EmployeeSearchController::class, 'search'])->name('api.employees.search');

// Visitor Registration Routes
Route::get('/register', function () {
    return view('visitor.register');
})->name('visitor.register');

Route::get('/visitor/success/{visitor}', [VisitorController::class, 'success'])
    ->name('visitor.success');

// Visitor Checkout Route (public - for immediate checkout after registration)
Route::post('/visitor/checkout/{visitor}', [VisitorController::class, 'visitorCheckout'])
    ->name('visitor.checkout');

// Visitor Authentication Routes
Route::prefix('visitor')->name('visitor.')->group(function () {
    // Login routes (guest only)
    Route::middleware('guest:visitor')->group(function () {
        Route::get('/login', [VisitorAuthController::class, 'showLogin'])->name('login');
        Route::post('/login/send-otp', [VisitorAuthController::class, 'sendLoginOTP'])->name('login.send-otp');
        Route::get('/login/verify', [VisitorAuthController::class, 'showVerifyOTP'])->name('login.verify');
        Route::post('/login/verify', [VisitorAuthController::class, 'verifyLoginOTP'])->name('login.verify.submit');
    });

    // Protected visitor routes
    Route::middleware('auth:visitor')->group(function () {
        Route::get('/dashboard', [VisitorProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [VisitorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [VisitorProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/mobile', [VisitorProfileController::class, 'editMobile'])->name('profile.edit-mobile');
        Route::post('/profile/mobile/send-otp', [VisitorProfileController::class, 'sendMobileOTP'])->name('profile.mobile.send-otp');
        Route::get('/profile/mobile/verify', [VisitorProfileController::class, 'verifyMobileForm'])->name('profile.verify-mobile');
        Route::post('/profile/mobile/verify', [VisitorProfileController::class, 'verifyMobileOTP'])->name('profile.mobile.verify');
        Route::get('/history', [VisitorProfileController::class, 'history'])->name('history');
        Route::post('/checkout', [VisitorProfileController::class, 'checkout'])->name('checkout');
        Route::post('/logout', [VisitorAuthController::class, 'logout'])->name('logout');
    });
});

// Admin/Dashboard Routes (optional)
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login routes (guest only)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'login'])->name('login.submit');
        
        // Password reset routes
        Route::get('/forgot-password', [App\Http\Controllers\Auth\AdminAuthController::class, 'showForgotPassword'])->name('password.request');
        Route::post('/forgot-password', [App\Http\Controllers\Auth\AdminAuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\AdminAuthController::class, 'showResetPassword'])->name('password.reset');
        Route::post('/reset-password', [App\Http\Controllers\Auth\AdminAuthController::class, 'resetPassword'])->name('password.update');
    });

    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
        Route::get('/visitors/{visitor}', [VisitorController::class, 'show'])->name('visitors.show');
        Route::post('/visitors/{visitor}/checkout', [VisitorController::class, 'checkout'])->name('visitors.checkout');
        
        // Sync management
        Route::get('/sync/status', [VisitorController::class, 'syncStatus'])->name('sync.status');
        Route::post('/sync/manual', [VisitorController::class, 'manualSync'])->name('sync.manual');

        // Logout
        Route::post('/logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->name('logout');
    });
});
