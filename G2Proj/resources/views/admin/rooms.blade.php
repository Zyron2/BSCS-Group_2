@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Rooms</h1>
    @if(session('success'))
        <div class="mb-4 alert alert-success text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.rooms.store') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Capacity</label>
            <input type="number" name="capacity" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-2">
            <label>Equipment (comma separated)</label>
            <input type="text" name="equipment" class="border rounded px-2 py-1 w-full" placeholder="e.g. Projector, Whiteboard">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Room</button>
    </form>
    <h2 class="text-xl font-semibold mb-4">Room List</h2>
    <table class="table-auto w-full mb-8">
        <thead>
            <tr>
                <th>Name</th>
                <th>Capacity</th>
                <th>Equipment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>
                        @if(is_array($room->equipment))
                            {{ implode(', ', $room->equipment) }}
                        @else
                            {{ is_string($room->equipment) ? implode(', ', json_decode($room->equipment, true) ?? []) : $room->equipment }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="text-blue-600">Edit</a>
                        <form method="POST" action="{{ route('admin.rooms.delete', $room) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="text-red-600 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
