<x-layouts.app title="Registration Successful">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden text-center p-8 sm:p-12">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">Registration Successful!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Welcome to Mayfair, <span class="font-semibold text-indigo-600">{{ $visitor->name }}</span>
            </p>

            <!-- Visitor Details Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Visitor Type:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($visitor->visitor_type) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mobile:</span>
                        <span class="font-medium text-gray-900">{{ $visitor->mobile }}</span>
                    </div>
                    @if ($visitor->email)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900">{{ $visitor->email }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Registration ID:</span>
                        <span class="font-medium text-indigo-600">{{ $visitor->registration_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-in Time:</span>
                        <span class="font-medium text-gray-900">{{ $visitor->checked_in_at->format('h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Your details have been recorded and synced to our system. 
                    Please proceed to the reception desk.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Checkout Button (if checked in) -->
                @if($visitor->status === 'checked_in' && is_null($visitor->checked_out_at))
                    <form action="{{ route('visitor.checkout', $visitor) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" 
                                class="block w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            ✓ Check Out Now
                        </button>
                    </form>
                @elseif($visitor->status === 'checked_out')
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-green-800">
                            <strong>✓ Checked Out</strong> at {{ $visitor->checked_out_at->format('h:i A') }}
                        </p>
                    </div>
                @endif

                <a href="{{ route('visitor.register') }}" 
                   class="block w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                    Register Another Visitor
                </a>
                <button onclick="window.print()" 
                        class="block w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Print Receipt
                </button>
            </div>

            <!-- Footer Message -->
            <p class="mt-8 text-sm text-gray-500">
                Thank you for visiting Mayfair. Have a great day! 🙏
            </p>
        </div>
    </div>
</x-layouts.app>
