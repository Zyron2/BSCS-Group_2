@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Action Buttons -->
    <div class="mb-6 flex gap-4">
        <a href="{{ route('admin.rooms') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Manage Rooms</a>
        <a href="{{ route('admin.bookings') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-200">Manage Schedules</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Rooms Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Rooms</h2>
                <a href="{{ route('admin.rooms') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All →</a>
            </div>
            
            @forelse($rooms as $room)
                <div class="border-b border-gray-200 py-3 flex justify-between items-center">
                    <div>
                        <div class="font-medium text-gray-900">{{ $room->name }}</div>
                        <div class="text-sm text-gray-500">Capacity: {{ $room->capacity }} people</div>
                    </div>
                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('admin.rooms.delete', $room) }}" onsubmit="return confirm('Delete this room? All bookings will be cancelled.')">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-300 rounded">
                            Delete
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No rooms available</p>
            @endforelse
        </div>

        <!-- Schedules Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Recent Schedules</h2>
                <a href="{{ route('admin.bookings') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All →</a>
            </div>
            
            @forelse($bookings->take(5) as $booking)
                @php
                    $statusColor = match($booking->status) {
                        'confirmed' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                
                <div class="border-b border-gray-200 py-3 last:border-b-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="font-medium text-gray-900">{{ $booking->title }}</div>
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $booking->room->name }} - {{ $booking->date }} 
                                ({{ $booking->start_time }}-{{ $booking->end_time }})
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Organizer: {{ $booking->organizer }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <!-- Delete Button - Fixed -->
                            <form method="POST" action="{{ route('admin.bookings.delete', $booking) }}" onsubmit="return confirm('Delete this booking? User will be notified.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition duration-200">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No schedules available</p>
            @endforelse
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-blue-50 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-blue-600 font-medium">Total Rooms</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $rooms->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-green-600 font-medium">Total Bookings</p>
                    <p class="text-2xl font-bold text-green-900">{{ $bookings->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-yellow-600 font-medium">Pending Bookings</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $bookings->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
