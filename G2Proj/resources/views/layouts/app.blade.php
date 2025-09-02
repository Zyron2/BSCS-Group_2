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
            <!-- Add nav links or user info here if needed -->
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>
