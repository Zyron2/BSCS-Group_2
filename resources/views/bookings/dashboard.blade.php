@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Book rooms and manage your reservations.</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Book a Room</h2>
                    
                    <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Room Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Room</label>
                            <select name="room_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Choose a room...</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ (isset($selectedRoomId) && $selectedRoomId == $room->id) ? 'selected' : '' }}>
                                        {{ $room->name }} (Capacity: {{ $room->capacity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Event Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                            <input type="text" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Meeting, Conference, etc." required>
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                <input type="time" name="start_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                <input type="time" name="end_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>

                        <!-- Organizer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organizer</label>
                            <input type="text" name="organizer" value="{{ auth()->user()->name }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Attendees -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Attendees</label>
                            <input type="number" name="attendees" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Expected attendees" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition duration-200 font-medium shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Book Room
                        </button>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 space-y-3">
                    <a href="{{ route('rooms.index') }}" class="w-full bg-gray-600 text-white text-center py-2 px-4 rounded-md hover:bg-gray-700 transition duration-200 font-medium shadow-md block">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Browse Rooms
                    </a>
                    
                    <a href="{{ route('bookings.index') }}" class="w-full bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition duration-200 font-medium shadow-md block">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Bookings
                    </a>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Today's Schedule</h2>
                            <div class="flex items-center space-x-4">
                                <!-- Date Filter -->
                                <form method="GET" class="flex items-center space-x-2">
                                    <input type="date" name="date" value="{{ $date }}" class="border border-gray-300 rounded-md px-3 py-1 text-sm" onchange="this.form.submit()">
                                    @if($search)
                                        <input type="hidden" name="search" value="{{ $search }}">
                                    @endif
                                    @if($roomId)
                                        <input type="hidden" name="room_id" value="{{ $roomId }}">
                                    @endif
                                </form>
                                
                                <!-- Room Filter -->
                                <form method="GET" class="flex items-center">
                                    <select name="room_id" class="border border-gray-300 rounded-md px-3 py-1 text-sm" onchange="this.form.submit()">
                                        <option value="">All Rooms</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($date)
                                        <input type="hidden" name="date" value="{{ $date }}">
                                    @endif
                                    @if($search)
                                        <input type="hidden" name="search" value="{{ $search }}">
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @forelse($bookings as $booking)
                            @php
                                $statusColor = match($booking->status) {
                                    'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                            @endphp

                            <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $booking->title }}</h3>
                                            <span class="px-2 py-1 rounded text-xs font-medium border {{ $statusColor }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <span>{{ $booking->room->name }}</span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span>{{ $booking->organizer }}</span>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <span>{{ $booking->attendees }} attendees</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
                                <p class="text-gray-500">Start by creating a new booking using the form on the left.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill end time based on start time (1 hour later)
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    
    startTimeInput.addEventListener('change', function() {
        if (this.value && !endTimeInput.value) {
            const startTime = new Date(`1970-01-01T${this.value}:00`);
            startTime.setHours(startTime.getHours() + 1);
            const endTime = startTime.toTimeString().slice(0, 5);
            endTimeInput.value = endTime;
        }
    });
});
</script>
@endsection