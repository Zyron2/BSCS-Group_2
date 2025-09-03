@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Available Rooms</h1>
            <p class="text-gray-600 mt-2">Browse and view details of all available rooms for booking.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $room->name }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Capacity: {{ $room->capacity }} people</span>
                            </div>
                        </div>

                        @if($room->about)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">About This Room</h4>
                                <p class="text-gray-600 text-sm">{{ $room->about }}</p>
                            </div>
                        @endif

                        @if($room->equipment && count(json_decode($room->equipment, true) ?? []) > 0)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Available Equipment</h4>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(json_decode($room->equipment, true) ?? [] as $item)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                            {{ $item }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t">
                            <a href="{{ route('bookings.index', ['room_id' => $room->id]) }}" 
                               class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded hover:bg-blue-700 transition duration-200 inline-block">
                                Book This Room
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2m5 0h2m-2 0v-2a2 2 0 012-2h2a2 2 0 012 2v2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No rooms available</h3>
                    <p class="mt-1 text-sm text-gray-500">Contact admin to add rooms.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('bookings.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition duration-200">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
