<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Visitor Management System' }} - Mayfair</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <!-- Alpine.js for reactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased bg-mayfair-dark min-h-screen">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="bg-mayfair-gray shadow-sm border-b border-mayfair-border">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Mayfair Logo" class="h-8 w-auto">
                        </div>
                        <button onclick="window.location.href='/admin/login'" type="button" class="text-sm text-mayfair-gold hover:text-yellow-300 transition-colors cursor-pointer bg-transparent border-0">
                            Admin Panel
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 py-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-mayfair-gray border-t border-mayfair-border py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-400">
                        &copy; {{ date('Y') }} Mayfair. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>

        <!-- Global Loader Component -->
        {{-- <x-loader /> --}}

        @livewireScripts
    </body>
</html>
