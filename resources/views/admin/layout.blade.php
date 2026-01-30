<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Mayfair VMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for reactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-indigo-600">Mayfair VMS Admin</h1>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('admin.visitors.index') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Visitors
                            </a>
                            <a href="{{ route('admin.sync.status') }}" 
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Sync Status
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Admin Info -->
                        @auth('admin')
                            <div class="flex items-center space-x-3">
                                <div class="hidden sm:block text-right">
                                    <p class="text-sm font-medium text-gray-700">{{ auth('admin')->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth('admin')->user()->department }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                        
                        <a href="{{ route('visitor.register') }}" 
                           class="text-sm text-indigo-600 hover:text-indigo-800">
                            New Registration
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Global Loader Component -->
    {{-- <x-loader /> --}}
</body>
</html>
