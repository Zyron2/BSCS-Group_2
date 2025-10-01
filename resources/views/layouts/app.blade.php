<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Add other CSS or meta tags as needed -->
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow mb-6">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <span class="font-bold text-xl text-blue-700">Room Booking System</span>
            
            @auth
                <div class="flex items-center space-x-4">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Admin Dashboard</a>
                        <a href="{{ route('admin.rooms') }}" class="text-blue-600 hover:text-blue-800">Manage Rooms</a>
                        <a href="{{ route('admin.bookings') }}" class="text-blue-600 hover:text-blue-800">Manage Bookings</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                        <a href="{{ route('rooms.index') }}" class="text-blue-600 hover:text-blue-800">View Rooms</a>
                        <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-800">My Bookings</a>
                    @endif
                
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button type="button"
                                onclick="document.getElementById('profileDropdown').classList.toggle('hidden')"
                                class="flex items-center space-x-2 focus:outline-none">
                            <img 
                                src="{{ auth()->user()->profile_url ?? asset('default-avatar.png') }}" 
                                class="w-8 h-8 rounded-full object-cover" 
                                alt="Avatar" 
                                onerror="this.onerror=null;this.src='{{ asset('default-avatar.png') }}'">
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="profileDropdown"
                             class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow z-50">
                            <a href="{{ route('profile.show') }}"
                               class="block px-4 py-2 text-sm hover:bg-gray-100">My Profile</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
                </div>
            @endauth
        </div>
    </nav>
    <main>
        @yield('content')
    </main>


    @include('layouts.footer')
    
    <!-- Small JS to close dropdown when clicking outside -->
    <script>
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const button = dropdown?.previousElementSibling;
            if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
