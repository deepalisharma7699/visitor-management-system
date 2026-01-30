<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Mayfair VMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .card-header {
            text-align: center;
            padding: 32px 24px 24px;
            background: #ffffff;
        }

        .logo {
           filter: invert(1);
           max-width: 150px;
           margin: auto;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 14px;
            color: #718096;
        }

        .card-body {
            padding: 0 32px 32px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            color: #1f2937;
            transition: all 0.2s ease;
            outline: none;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #9ca3af;
        }

        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-label {
            font-size: 14px;
            color: #374151;
            margin: 0;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #4f46e5;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .card-footer {
            background: #f9fafb;
            padding: 16px 32px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-link {
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            color: #6366f1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: #4f46e5;
        }

        .copyright {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Card -->
        <div class="login-card">
            <!-- Card Header -->
            <div class="card-header">               
                <img src="{{ asset('images/logo.png') }}" alt="Mayfair Logo" class="logo">
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required 
                               autofocus
                               autocomplete="username"
                               placeholder="admin@mayfair.com">
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
                               autocomplete="current-password"
                               placeholder="Enter your password">
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="checkbox-group">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" 
                                   id="remember"
                                   name="remember">
                            <label for="remember" class="checkbox-label">Remember me</label>
                        </div>
                        <a href="{{ route('admin.password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        Sign In
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <a href="{{ route('visitor.register') }}" class="footer-link">
                    ← Back to Visitor Portal
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            &copy; {{ date('Y') }} Mayfair. All rights reserved.
        </div>
    </div>
</body>
</html>
