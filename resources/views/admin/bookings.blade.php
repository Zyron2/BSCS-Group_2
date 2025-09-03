@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Schedules</h1>
    @if(session('success'))
        <div class="mb-4 alert alert-success text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.bookings.store') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>Room</label>
            <select name="room_id" class="border rounded px-2 py-1 w-full" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Title</label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Date</label>
            <input type="date" name="date" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Start Time</label>
            <input type="time" name="start_time" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>End Time</label>
            <input type="time" name="end_time" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Organizer</label>
            <input type="text" name="organizer" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Attendees</label>
            <input type="number" name="attendees" class="border rounded px-2 py-1 w-full" required>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Booking</button>
    </form>
    <h2 class="text-xl font-semibold mb-4">Schedule List</h2>
    <table class="table-auto w-full mb-8">
        <thead>
            <tr>
                <th>Room</th>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Organizer</th>
                <th>Attendees</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->room->name }}</td>
                    <td>{{ $booking->title }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                    <td>{{ $booking->organizer }}</td>
                    <td>{{ $booking->attendees }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
