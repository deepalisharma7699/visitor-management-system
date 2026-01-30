<x-layouts.app title="Change Mobile Number">
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Change Mobile Number
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your new mobile number to receive OTP
                </p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Current Mobile:</p>
                    <p class="font-medium text-gray-900">{{ $visitor->mobile }}</p>
                </div>

                <form action="{{ route('visitor.profile.mobile.send-otp') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700">Country Code</label>
                        <select name="country_code" id="country_code" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($countries as $country)
                                <option value="{{ $country['dial_code'] }}" {{ $country['dial_code'] === '+91' ? 'selected' : '' }}>
                                    {{ $country['flag'] }} {{ $country['name'] }} ({{ $country['dial_code'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">New Mobile Number</label>
                        <input type="text" name="mobile" id="mobile" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('mobile') border-red-500 @enderror"
                            placeholder="Enter new mobile number">
                        @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send OTP
                        </button>
                        <a href="{{ route('visitor.profile.edit') }}"
                            class="flex-1 py-2 px-4 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
