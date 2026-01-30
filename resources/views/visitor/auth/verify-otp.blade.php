<x-layouts.app title="Verify OTP">
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-mayfair-gray rounded-2xl shadow-2xl overflow-hidden border border-mayfair-border">
        <div class="p-6 sm:p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">
                    Verify OTP
                </h2>
                <p class="text-gray-400">
                    Enter the 4-digit code sent to your registered contact
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-900/30 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('visitor.login.verify.submit') }}" method="POST" id="verifyForm">
                @csrf
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-300 mb-2">
                        OTP Code
                    </label>
                    <input id="otp" name="otp" type="text" required maxlength="4" pattern="[0-9]{4}"
                        class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white text-center text-2xl tracking-widest font-mono @error('otp') border-red-500 @enderror"
                        placeholder="0000"
                        autofocus>
                    @error('otp')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 text-center">
                        OTP is valid for 5 minutes
                    </p>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-mayfair-dark bg-mayfair-gold hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mayfair-gold transition-colors">
                        <span class="submit-text">Verify & Login</span>
                        <span class="submit-loader hidden">
                            <svg class="animate-spin h-5 w-5 text-mayfair-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('visitor.login') }}" class="text-sm font-medium text-mayfair-gold hover:text-yellow-500">
                        ← Back to login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    const text = btn.querySelector('.submit-text');
    const loader = btn.querySelector('.submit-loader');
    
    btn.disabled = true;
    text.classList.add('hidden');
    loader.classList.remove('hidden');
});
</script>
</x-layouts.app>
