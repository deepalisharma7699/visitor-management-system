@extends('layouts.app')

@section('title', 'Check-in Successful')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg text-center">
        <div>
            <div class="mx-auto h-24 w-24 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-5xl text-green-600"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Check-in Successful!
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                Thank you for registering. You have been checked in successfully.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Important Information:</h3>
                <ul class="text-sm text-gray-600 space-y-2 text-left">
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                        <span>Please keep your visitor badge visible at all times</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                        <span>Don't forget to check out when you leave</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                        <span>If you need assistance, please contact the reception</span>
                    </li>
                </ul>
            </div>

            <div class="pt-6">
                <a href="{{ route('visitor.self-register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </a>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Check-in Time: {{ now()->format('F j, Y g:i A') }}
            </p>
        </div>
    </div>
</div>
@endsection
