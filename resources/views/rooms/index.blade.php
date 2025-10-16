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
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Room Header -->
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $room->name }}</h3>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium">Capacity: {{ $room->capacity }} people</span>
                            </div>
                        </div>

                        <!-- Room Description -->
                        @if($room->about)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    About This Room
                                </h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $room->about }}</p>
                            </div>
                        @endif

                        <!-- Equipment Section -->
                        @if($room->equipment && count(json_decode($room->equipment, true) ?? []) > 0)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Available Equipment
                                </h4>
                                
                                @php
                                    $equipmentList = json_decode($room->equipment, true) ?? [];
                                    $displayCount = 3;
                                @endphp

                                <div class="space-y-2">
                                    <!-- Always visible equipment -->
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($equipmentList, 0, $displayCount) as $item)
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                {{ trim($item) }}
                                            </span>
                                        @endforeach
                                        
                                        @if(count($equipmentList) > $displayCount)
                                            <button onclick="toggleEquipment({{ $room->id }})" 
                                                    class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full hover:bg-blue-200 transition-colors cursor-pointer">
                                                +{{ count($equipmentList) - $displayCount }} more
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Hidden equipment (initially hidden) -->
                                    @if(count($equipmentList) > $displayCount)
                                        <div id="equipment-{{ $room->id }}" class="hidden">
                                            <div class="border-t pt-2 mt-2">
                                                <p class="text-xs text-gray-500 mb-2 font-medium">Complete Equipment List:</p>
                                                <div class="grid grid-cols-1 gap-1">
                                                    @foreach($equipmentList as $index => $item)
                                                        <div class="flex items-center text-sm text-gray-700">
                                                            <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            {{ trim($item) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button onclick="toggleEquipment({{ $room->id }})" 
                                                        class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">
                                                    Show less
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Equipment
                                </h4>
                                <p class="text-gray-500 text-sm italic">No equipment information available</p>
                            </div>
                        @endif

                        <!-- Book Button -->
                        <div class="pt-4 border-t">
                            <a href="{{ route('dashboard', ['room_id' => $room->id]) }}" 
                               class="w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 inline-block font-medium shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Book This Room
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2m5 0h2m-2 0v-2a2 2 0 012-2h2a2 2 0 012 2v2"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No rooms available</h3>
                    <p class="text-gray-500">Contact admin to add rooms for booking.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-medium shadow-md">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
function toggleEquipment(roomId) {
    const equipmentDiv = document.getElementById('equipment-' + roomId);
    
    if (equipmentDiv.classList.contains('hidden')) {
        equipmentDiv.classList.remove('hidden');
        equipmentDiv.classList.add('animate-fadeIn');
    } else {
        equipmentDiv.classList.add('hidden');
        equipmentDiv.classList.remove('animate-fadeIn');
    }
}
</script>

<style>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
