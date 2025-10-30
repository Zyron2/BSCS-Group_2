@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Bookings</h1>
    
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                <select name="room_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="">All Rooms</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Bookings List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->title }}</div>
                                <div class="text-sm text-gray-500">Organizer: {{ $booking->organizer }}</div>
                                <div class="text-sm text-gray-500">Attendees: {{ $booking->attendees }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $booking->room->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->date }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->start_time }} - {{ $booking->end_time }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded px-2 py-1 {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="rejected" {{ $booking->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.delete', $booking) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking? The user will be notified.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 px-3 py-1 rounded border border-red-300 hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No bookings found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
