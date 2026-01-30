<x-layouts.app title="Dashboard">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Welcome, {{ $visitor->name }}!</h1>
            <p class="text-gray-400 mt-1">Visitor ID: #{{ str_pad($visitor->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <form action="{{ route('visitor.logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="submit" class="px-4 py-2 bg-mayfair-border text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                <span class="logout-text">Logout</span>
                <span class="logout-loader hidden">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-mayfair-gray rounded-lg border border-mayfair-border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-mayfair-gold/20 rounded-md p-3">
                    <svg class="h-6 w-6 text-mayfair-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Visitor Type</p>
                    <p class="text-lg font-semibold text-white">{{ ucfirst($visitor->visitor_type) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-mayfair-gray rounded-lg border border-mayfair-border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-900/30 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Status</p>
                    <p class="text-lg font-semibold text-white">{{ ucfirst(str_replace('_', ' ', $visitor->status)) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-mayfair-gray rounded-lg border border-mayfair-border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-900/30 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Registered</p>
                    <p class="text-lg font-semibold text-white">{{ $visitor->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-mayfair-gray rounded-lg border border-mayfair-border p-6 mb-8">
        <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Checkout Button (if checked in) -->
            @if($visitor->status === 'checked_in' && is_null($visitor->checked_out_at))
                <form action="{{ route('visitor.checkout') }}" method="POST" class="md:col-span-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center p-4 border-2 border-green-700 bg-green-900/30 rounded-lg hover:bg-green-700 hover:border-green-600 transition text-center">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-4 text-left">
                            <p class="text-lg font-medium text-green-300">Check Out Now</p>
                            <p class="text-sm text-green-400">Complete your visit</p>
                        </div>
                    </button>
                </form>
            @endif

            <a href="{{ route('visitor.profile.edit') }}" 
               class="flex items-center p-4 border border-mayfair-border rounded-lg hover:bg-mayfair-dark hover:border-mayfair-gold transition">
                <svg class="h-8 w-8 text-mayfair-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <div class="ml-4">
                    <p class="text-lg font-medium text-white">Edit Profile</p>
                    <p class="text-sm text-gray-400">Update your information</p>
                </div>
            </a>

            <a href="{{ route('visitor.history') }}" 
               class="flex items-center p-4 border border-mayfair-border rounded-lg hover:bg-mayfair-dark hover:border-mayfair-gold transition">
                <svg class="h-8 w-8 text-mayfair-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-4">
                    <p class="text-lg font-medium text-white">Visit History</p>
                    <p class="text-sm text-gray-400">View your past visits</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-mayfair-gray rounded-lg border border-mayfair-border p-6">
        <h2 class="text-xl font-semibold text-white mb-4">Profile Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border-b border-mayfair-border pb-4">
                <dt class="text-sm font-medium text-gray-400">Name</dt>
                <dd class="mt-1 text-sm text-white">{{ $visitor->name }}</dd>
            </div>
            <div class="border-b border-mayfair-border pb-4">
                <dt class="text-sm font-medium text-gray-400">Mobile</dt>
                <dd class="mt-1 text-sm text-white">{{ $visitor->mobile }}</dd>
            </div>
            <div class="border-b border-mayfair-border pb-4">
                <dt class="text-sm font-medium text-gray-400">Email</dt>
                <dd class="mt-1 text-sm text-white">{{ $visitor->email ?? 'Not provided' }}</dd>
            </div>
            <div class="border-b border-mayfair-border pb-4">
                <dt class="text-sm font-medium text-gray-400">Visitor Type</dt>
                <dd class="mt-1 text-sm text-white">{{ ucfirst($visitor->visitor_type) }}</dd>
            </div>

            @if($visitor->visitor_type === 'guest')
                <div class="border-b border-mayfair-border pb-4">
                    <dt class="text-sm font-medium text-gray-400">Guest Type</dt>
                    <dd class="mt-1 text-sm text-white">{{ ucfirst($visitor->guest_type ?? 'N/A') }}</dd>
                </div>
                @if($visitor->company_name)
                    <div class="border-b border-mayfair-border pb-4">
                        <dt class="text-sm font-medium text-gray-400">Company Name</dt>
                        <dd class="mt-1 text-sm text-white">{{ $visitor->company_name }}</dd>
                    </div>
                @endif
                <div class="border-b border-mayfair-border pb-4">
                    <dt class="text-sm font-medium text-gray-400">Whom to Meet</dt>
                    <dd class="mt-1 text-sm text-white">{{ $visitor->employee->name ?? 'N/A' }}</dd>
                </div>
            @elseif($visitor->visitor_type === 'broker')
                <div class="border-b border-mayfair-border pb-4">
                    <dt class="text-sm font-medium text-gray-400">Broker Company</dt>
                    <dd class="mt-1 text-sm text-white">{{ $visitor->broker_company ?? 'N/A' }}</dd>
                </div>
                <div class="border-b border-mayfair-border pb-4">
                    <dt class="text-sm font-medium text-gray-400">Meeting Department</dt>
                    <dd class="mt-1 text-sm text-white">{{ $visitor->meet_department ?? 'N/A' }}</dd>
                </div>
            @elseif($visitor->visitor_type === 'customer')
                <div class="border-b border-mayfair-border pb-4">
                    <dt class="text-sm font-medium text-gray-400">Interested Project</dt>
                    <dd class="mt-1 text-sm text-white">{{ $visitor->project->name ?? 'N/A' }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>

<script>
document.getElementById('logoutForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    const text = btn.querySelector('.logout-text');
    const loader = btn.querySelector('.logout-loader');
    
    btn.disabled = true;
    text.classList.add('hidden');
    loader.classList.remove('hidden');
});
</script>
</x-layouts.app>
