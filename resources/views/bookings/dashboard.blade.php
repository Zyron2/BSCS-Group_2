@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 alert alert-success text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2">
                {{ session('success') }}
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Date Picker -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Select Date</h3>
                    <form method="GET" action="{{ route('bookings.index') }}">
                        <input type="date" name="date" value="{{ $date }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4" />
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Filter by Date</button>
                    </form>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>
                    <form method="GET" action="{{ route('bookings.index') }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search bookings..." class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                            <select name="room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Rooms</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Apply Filters</button>
                    </form>
                </div>

                <!-- Notifications (static for now) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Notifications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <span class="text-yellow-500">&#9888;</span>
                            <div>
                                <p class="text-sm text-gray-800">Conference Room A booking moved to 10:00 AM</p>
                                <p class="text-xs text-gray-500">5 min ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-red-500">&#10060;</span>
                            <div>
                                <p class="text-sm text-gray-800">Lab Room D booking cancelled for tomorrow</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-green-500">&#10003;</span>
                            <div>
                                <p class="text-sm text-gray-800">New booking request for Seminar Room E</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow mb-8">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-xl font-semibold">Schedule for {{ $date }}</h2>
                        <!-- Book Room Button triggers modal -->
                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded flex items-center"
                            data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <span class="mr-2">+</span> Book Room
                        </button>
                    </div>
                    <div class="p-6">
                        @if($bookings->isEmpty())
                            <div class="text-center py-12">
                                <span class="text-gray-400 text-4xl">&#128197;</span>
                                <p class="text-gray-500 mt-4">No bookings found for this date</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($bookings as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h3 class="text-lg font-semibold">{{ $booking->title }}</h3>
                                                    <span class="px-2 py-1 rounded-full text-xs
                                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                                    <span>&#128337; {{ $booking->start_time }} - {{ $booking->end_time }}</span>
                                                    <span>&#128205; {{ $booking->room->name }}</span>
                                                    <span>&#128101; {{ $booking->attendees }} attendees</span>
                                                </div>
                                                <p class="text-sm text-gray-600">Organized by {{ $booking->organizer }}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                                <button class="text-red-600 hover:text-red-800 text-sm">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Available Rooms</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($rooms as $room)
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-gray-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm">
                                            {{ $room->capacity }} seats
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Equipment:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @if(is_array($room->equipment))
                                                @foreach($room->equipment as $item)
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">{{ $item }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-400">None</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Today's Bookings:</h4>
                                        <div class="space-y-1">
                                            @php
                                                $roomBookings = $bookings->filter(function($b) use ($room, $date) {
                                                    return $b->room_id == $room->id && $b->date == $date;
                                                });
                                            @endphp
                                            @forelse($roomBookings as $booking)
                                                <div class="text-sm text-gray-600">
                                                    {{ $booking->start_time }} - {{ $booking->end_time }}: {{ $booking->title }}
                                                </div>
                                            @empty
                                                <p class="text-sm text-green-600">Available all day</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    <!-- Book Room Button triggers modal -->
                                    <button type="button" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700"
                                        data-bs-toggle="modal" data-bs-target="#bookingModal">
                                        Book Room
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <div class="modal-content p-4">
                    <h3 class="text-lg font-semibold mb-4">Book a Room</h3>
                    <div class="mb-2">
                        <label>Room</label>
                        <select name="room_id" class="w-full border rounded px-2 py-1" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} (Capacity: {{ $room->capacity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Event Title</label>
                        <input type="text" name="title" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Organizer</label>
                        <input type="text" name="organizer" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Number of Attendees</label>
                        <input type="number" name="attendees" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="px-4 py-2 text-gray-600" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Book Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap JS for modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
