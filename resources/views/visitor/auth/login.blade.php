<x-layouts.app title="Visitor Login">
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-mayfair-gray rounded-2xl shadow-2xl overflow-hidden border border-mayfair-border">
        <div class="p-6 sm:p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">
                    Welcome Back
                </h2>
                <p class="text-gray-400">
                    Sign in to your visitor account
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

            <form class="space-y-6" action="{{ route('visitor.login.send-otp') }}" method="POST" id="loginForm">
                @csrf
                <div>
                    <label for="identifier" class="block text-sm font-medium text-gray-300 mb-2">
                        Email or Mobile Number
                    </label>
                    <input id="identifier" name="identifier" type="text" required
                        class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500 @error('identifier') border-red-500 @enderror"
                        placeholder="Enter your email or mobile number"
                        value="{{ old('identifier') }}">
                    @error('identifier')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Enter the email or mobile number you registered with
                    </p>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-mayfair-dark bg-mayfair-gold hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mayfair-gold transition-colors">
                        <span class="submit-text">Send OTP</span>
                        <span class="submit-loader hidden">
                            <svg class="animate-spin h-5 w-5 text-mayfair-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-400">
                        Don't have an account?
                        <a href="{{ route('visitor.register') }}" class="font-medium text-mayfair-gold hover:text-yellow-500">
                            Register here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    const text = btn.querySelector('.submit-text');
    const loader = btn.querySelector('.submit-loader');
    
    btn.disabled = true;
    text.classList.add('hidden');
    loader.classList.remove('hidden');
});
</script>
</x-layouts.app>
