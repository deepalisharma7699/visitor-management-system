@extends('admin.layout')

@section('title', 'Visitor Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.visitors.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
        ← Back to Visitors List
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Visitor Details</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Registration ID</label>
                    <p class="text-lg font-semibold text-indigo-600">#{{ str_pad($visitor->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Visitor Type</label>
                    <p class="text-lg font-semibold">{{ ucfirst($visitor->visitor_type) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Full Name</label>
                    <p class="text-lg">{{ $visitor->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Mobile Number</label>
                    <p class="text-lg">+91 {{ $visitor->mobile }}</p>
                </div>
                @if ($visitor->email)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Email Address</label>
                        <p class="text-lg">{{ $visitor->email }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Type Specific Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Details</h2>
            
            @if ($visitor->visitor_type === 'guest')
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Guest Type</label>
                        <p class="text-lg">{{ ucfirst(str_replace('_', ' ', $visitor->guest_type ?? 'N/A')) }}</p>
                    </div>
                    @if ($visitor->company_name)
                        <div>
                            <label class="text-sm text-gray-500">Company Name</label>
                            <p class="text-lg">{{ $visitor->company_name }}</p>
                        </div>
                    @endif
                    @if ($visitor->employee)
                        <div>
                            <label class="text-sm text-gray-500">Meeting With</label>
                            <p class="text-lg">{{ $visitor->employee->name }} ({{ $visitor->employee->department }})</p>
                        </div>
                    @endif
                    @if ($visitor->accompanying_count > 0)
                        <div>
                            <label class="text-sm text-gray-500">Accompanying Persons</label>
                            <p class="text-lg">{{ $visitor->accompanying_count }} person(s)</p>
                            @if ($visitor->accompanying_persons)
                                <div class="mt-2 space-y-2">
                                    @foreach ($visitor->accompanying_persons as $person)
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="font-medium">{{ $person['name'] }}</p>
                                            @if (!empty($person['mobile']))
                                                <p class="text-sm text-gray-600">📱 {{ $person['mobile'] }}</p>
                                            @endif
                                            @if (!empty($person['email']))
                                                <p class="text-sm text-gray-600">✉️ {{ $person['email'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @elseif ($visitor->visitor_type === 'broker')
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Company Name</label>
                        <p class="text-lg">{{ $visitor->broker_company }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Department to Meet</label>
                        <p class="text-lg">{{ $visitor->meet_department }}</p>
                    </div>
                </div>
            @elseif ($visitor->visitor_type === 'customer')
                <div class="space-y-3">
                    @if ($visitor->project)
                        <div>
                            <label class="text-sm text-gray-500">Interested Project</label>
                            <p class="text-lg font-medium">{{ $visitor->project->name }}</p>
                            <p class="text-sm text-gray-600">{{ $visitor->project->location }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Status</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Current Status</label>
                    <p class="text-lg">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            {{ $visitor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $visitor->status === 'verified' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $visitor->status === 'checked_in' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $visitor->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $visitor->status)) }}
                        </span>
                    </p>
                </div>
                
                @if ($visitor->verified_at)
                    <div>
                        <label class="text-sm text-gray-500">Verified At</label>
                        <p class="text-base">{{ $visitor->verified_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif

                @if ($visitor->checked_in_at)
                    <div>
                        <label class="text-sm text-gray-500">Check-in Time</label>
                        <p class="text-base">{{ $visitor->checked_in_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif

                @if ($visitor->checked_out_at)
                    <div>
                        <label class="text-sm text-gray-500">Check-out Time</label>
                        <p class="text-base">{{ $visitor->checked_out_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif

                @if ($visitor->status === 'checked_in')
                    <form action="{{ route('admin.visitors.checkout', $visitor) }}" method="POST" class="pt-2">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Check Out Visitor
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Sync Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Sync Status</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-500">Google Sheets</label>
                    @if ($visitor->synced_to_sheets)
                        <p class="text-green-600 font-medium">✓ Synced</p>
                        @if ($visitor->synced_at)
                            <p class="text-xs text-gray-500">{{ $visitor->synced_at->diffForHumans() }}</p>
                        @endif
                    @else
                        <p class="text-orange-600 font-medium">⏳ Pending</p>
                        <p class="text-xs text-gray-500">Will sync automatically</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
