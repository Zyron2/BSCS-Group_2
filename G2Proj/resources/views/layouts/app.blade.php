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
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                        </form>
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
</body>
</html>
