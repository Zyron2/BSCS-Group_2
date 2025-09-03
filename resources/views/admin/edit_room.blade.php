@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Room</h1>
    @if(session('success'))
        <div class="mb-4 alert alert-success text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" value="{{ $room->name }}" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Capacity</label>
            <input type="number" name="capacity" value="{{ $room->capacity }}" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>About</label>
            <textarea name="about" class="border rounded px-2 py-1 w-full" rows="3" placeholder="Room description...">{{ $room->about }}</textarea>
        </div>
        <div class="mb-2">
            <label>Equipment (comma separated)</label>
            <input type="text" name="equipment" value="{{ is_array($room->equipment) ? implode(', ', $room->equipment) : (is_string($room->equipment) ? implode(', ', json_decode($room->equipment, true) ?? []) : $room->equipment) }}" class="border rounded px-2 py-1 w-full" placeholder="e.g. Projector, Whiteboard">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Room</button>
        <a href="{{ route('admin.rooms') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</a>
    </form>
</div>
@endsection
