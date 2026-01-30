<x-layouts.app title="Visit History">
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Visit History</h1>
                    <p class="text-gray-600 mt-1">Your visit records with Mayfair</p>
                </div>
                <a href="{{ route('visitor.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Back to Dashboard
                </a>
            </div>

            <!-- Visit Details Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Current Visit</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Registration Date</p>
                            <p class="text-lg font-medium text-gray-900">{{ $visitor->created_at->format('F d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $visitor->created_at->format('h:i A') }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $visitor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $visitor->status === 'verified' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $visitor->status === 'checked_in' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $visitor->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $visitor->status)) }}
                            </span>
                        </div>
                    </div>

                    @if($visitor->checked_in_at)
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                            <div>
                                <p class="text-sm text-gray-500">Check-in Time</p>
                                <p class="text-lg font-medium text-gray-900">{{ $visitor->checked_in_at->format('F d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $visitor->checked_in_at->format('h:i A') }}</p>
                            </div>
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif

                    @if($visitor->checked_out_at)
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                            <div>
                                <p class="text-sm text-gray-500">Check-out Time</p>
                                <p class="text-lg font-medium text-gray-900">{{ $visitor->checked_out_at->format('F d, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $visitor->checked_out_at->format('h:i A') }}</p>
                            </div>
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                    @endif

                    @if($visitor->checked_in_at && $visitor->checked_out_at)
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <p class="text-sm text-indigo-600 font-medium">Total Duration</p>
                            <p class="text-2xl font-bold text-indigo-900">
                                {{ $visitor->checked_in_at->diffForHumans($visitor->checked_out_at, true) }}
                            </p>
                        </div>
                    @elseif($visitor->checked_in_at)
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-green-600 font-medium">Currently Checked In</p>
                            <p class="text-lg font-semibold text-green-900">
                                Duration: {{ $visitor->checked_in_at->diffForHumans(null, true) }}
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                        <div>
                            <p class="text-sm text-gray-500">Visitor Type</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($visitor->visitor_type) }}</p>
                        </div>
                        
                        @if($visitor->visitor_type === 'guest' && $visitor->employee)
                            <div>
                                <p class="text-sm text-gray-500">Met With</p>
                                <p class="font-medium text-gray-900">{{ $visitor->employee->name }}</p>
                                <p class="text-xs text-gray-500">{{ $visitor->employee->department }}</p>
                            </div>
                        @endif

                        @if($visitor->visitor_type === 'customer' && $visitor->project)
                            <div>
                                <p class="text-sm text-gray-500">Interested Project</p>
                                <p class="font-medium text-gray-900">{{ $visitor->project->name }}</p>
                                <p class="text-xs text-gray-500">{{ $visitor->project->location }}</p>
                            </div>
                        @endif

                        @if($visitor->synced_to_sheets)
                            <div>
                                <p class="text-sm text-gray-500">Google Sheets Sync</p>
                                <p class="font-medium text-green-600">✓ Synced</p>
                                @if($visitor->synced_at)
                                    <p class="text-xs text-gray-500">{{ $visitor->synced_at->format('M d, Y H:i') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Visit Information</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            This shows your current visit record. Your visit history is maintained for security and record-keeping purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
