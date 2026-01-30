<x-layouts.app title="Edit Profile">
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="text-gray-600 mt-1">Update your visitor information</p>
                </div>
                <a href="{{ route('visitor.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Back to Dashboard
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Profile Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form action="{{ route('visitor.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Basic Information</h3>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $visitor->name) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                            <div class="mt-1">
                                <input type="text" value="{{ $visitor->mobile }}" disabled
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                                <p class="mt-1 text-xs text-gray-500">
                                    To change your mobile number, 
                                    <a href="{{ route('visitor.profile.edit-mobile') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">click here</a>
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $visitor->email) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Guest Specific Fields -->
                    @if($visitor->visitor_type === 'guest')
                        <div class="mt-8 space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Guest Information</h3>

                            <div>
                                <label for="guest_type" class="block text-sm font-medium text-gray-700">Guest Type <span class="text-red-500">*</span></label>
                                <select name="guest_type" id="guest_type" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('guest_type') border-red-500 @enderror"
                                    onchange="toggleCompanyField(this.value)">
                                    <option value="">Select Guest Type</option>
                                    <option value="friend" {{ old('guest_type', $visitor->guest_type) === 'friend' ? 'selected' : '' }}>Friend</option>
                                    <option value="family" {{ old('guest_type', $visitor->guest_type) === 'family' ? 'selected' : '' }}>Family</option>
                                    <option value="vendor" {{ old('guest_type', $visitor->guest_type) === 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    <option value="contractor" {{ old('guest_type', $visitor->guest_type) === 'contractor' ? 'selected' : '' }}>Contractor</option>
                                    <option value="other" {{ old('guest_type', $visitor->guest_type) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('guest_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="company_field" style="display: {{ in_array(old('guest_type', $visitor->guest_type), ['vendor', 'contractor']) ? 'block' : 'none' }}">
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name <span class="text-red-500">*</span></label>
                                <input type="text" name="company_name" id="company_name"
                                    value="{{ old('company_name', $visitor->company_name) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('company_name') border-red-500 @enderror">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whom_to_meet" class="block text-sm font-medium text-gray-700">Whom to Meet <span class="text-red-500">*</span></label>
                                <select name="whom_to_meet" id="whom_to_meet" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('whom_to_meet') border-red-500 @enderror">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('whom_to_meet', $visitor->whom_to_meet) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->department }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('whom_to_meet')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Broker Specific Fields -->
                    @if($visitor->visitor_type === 'broker')
                        <div class="mt-8 space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Broker Information</h3>

                            <div>
                                <label for="broker_company" class="block text-sm font-medium text-gray-700">Broker Company <span class="text-red-500">*</span></label>
                                <input type="text" name="broker_company" id="broker_company" required
                                    value="{{ old('broker_company', $visitor->broker_company) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('broker_company') border-red-500 @enderror">
                                @error('broker_company')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="meet_department" class="block text-sm font-medium text-gray-700">Department to Meet <span class="text-red-500">*</span></label>
                                <input type="text" name="meet_department" id="meet_department" required
                                    value="{{ old('meet_department', $visitor->meet_department) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('meet_department') border-red-500 @enderror">
                                @error('meet_department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Customer Specific Fields -->
                    @if($visitor->visitor_type === 'customer')
                        <div class="mt-8 space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Customer Information</h3>

                            <div>
                                <label for="interested_project" class="block text-sm font-medium text-gray-700">Interested Project <span class="text-red-500">*</span></label>
                                <select name="interested_project" id="interested_project" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('interested_project') border-red-500 @enderror">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('interested_project', $visitor->interested_project) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->location }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('interested_project')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($visitor->visitor_type === 'guest')
    <script>
        function toggleCompanyField(guestType) {
            const companyField = document.getElementById('company_field');
            const companyInput = document.getElementById('company_name');
            
            if (guestType === 'vendor' || guestType === 'contractor') {
                companyField.style.display = 'block';
                companyInput.required = true;
            } else {
                companyField.style.display = 'none';
                companyInput.required = false;
                companyInput.value = '';
            }
        }
    </script>
    @endif
</x-layouts.app>
