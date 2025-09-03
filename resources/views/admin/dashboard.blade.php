@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    <a href="{{ route('admin.rooms') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Manage Rooms</a>
    <a href="{{ route('admin.bookings') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">Manage Schedules</a>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-semibold mb-4">Rooms</h2>
            <ul>
                @foreach($rooms as $room)
                    <li class="mb-2">{{ $room->name }} (Capacity: {{ $room->capacity }})</li>
                @endforeach
            </ul>
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-4">Schedules</h2>
            <ul>
                @foreach($bookings as $booking)
                    <li class="mb-2">{{ $booking->title }} - {{ $booking->room->name }} ({{ $booking->date }} {{ $booking->start_time }}-{{ $booking->end_time }})</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
