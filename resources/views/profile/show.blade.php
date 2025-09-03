@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-xl shadow">
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <h1 class="text-xl font-semibold mb-4">My Profile</h1>

    <div class="flex items-center space-x-4 mb-6">
        <img src="{{ auth()->user()->profile_url }}" 
             class="w-20 h-20 rounded-full object-cover" alt="Avatar">
        <div>
            <div class="text-sm text-gray-600">Current avatar</div>
            <div class="text-gray-900 font-medium">{{ auth()->user()->name }}</div>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                   class="w-full border rounded-lg p-2">
            @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Change Profile Photo</label>
            <input type="file" name="profile" accept="image/*" class="w-full border rounded-lg p-2">
            @error('profile') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Changes</button>
    </form>
</div>
@endsection
