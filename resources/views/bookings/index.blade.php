@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">My Bookings</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your room bookings and reservations.</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Bookings List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            @forelse($bookings as $booking)
                @php
                    $bookingDateTime = \Carbon\Carbon::parse($booking->date . ' ' . $booking->start_time);
                    $canCancel = !$bookingDateTime->isPast();
                    $statusColor = match($booking->status) {
                        'confirmed' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                        'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                        'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
                        default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
                    };
                @endphp

                <div class="border-b border-gray-200 dark:border-gray-700 p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <!-- Booking Header -->
                            <div class="flex items-center space-x-4 mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $booking->title }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <!-- Booking Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-medium">Room:</span>
                                    <span class="ml-1">{{ $booking->room->name }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium">Date:</span>
                                    <span class="ml-1">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Time:</span>
                                    <span class="ml-1">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            @if($booking->organizer || $booking->attendees)
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($booking->organizer)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="font-medium">Organizer:</span>
                                            <span class="ml-1">{{ $booking->organizer }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($booking->attendees)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span class="font-medium">Attendees:</span>
                                            <span class="ml-1">{{ $booking->attendees }} people</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col space-y-2 ml-6">
                            @if($canCancel && in_array($booking->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="inline" onsubmit="return confirmCancel()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Cancel Booking
                                    </button>
                                </form>
                            @else
                                @if($bookingDateTime->isPast())
                                    <span class="text-gray-500 dark:text-gray-400 text-sm italic">Past booking</span>
                                @elseif($booking->status === 'rejected')
                                    <span class="text-red-500 dark:text-red-400 text-sm italic">Rejected</span>
                                @endif
                            @endif

                            <!-- Booking Status Indicator -->
                            <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                @if($bookingDateTime->isPast())
                                    Completed
                                @elseif($bookingDateTime->isToday())
                                    Today
                                @elseif($bookingDateTime->isTomorrow())
                                    Tomorrow
                                @else
                                    {{ $bookingDateTime->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No bookings found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">You haven't made any room bookings yet.</p>
                    <a href="{{ route('rooms.index') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium shadow-md">
                        Browse Available Rooms
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('rooms.index') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-medium shadow-md">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Book New Room
            </a>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-600 dark:bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition duration-200 font-medium shadow-md">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
function confirmCancel() {
    return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');
}
</script>
@endsection
