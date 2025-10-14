<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Room Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Add other CSS or meta tags as needed -->
    
    <!-- Custom Styles for Enhanced Header -->
    <style>
        /* Custom gradient animations */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-animated {
            background: linear-gradient(-45deg, #3b82f6, #8b5cf6, #6366f1, #3b82f6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        /* Smooth transitions */
        .transition-all-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Glass morphism effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Profile dropdown animation */
        .dropdown-enter {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        
        .dropdown-enter-active {
            opacity: 1;
            transform: translateY(0) scale(1);
            transition: all 0.2s ease-out;
        }
        
        /* Notification pulse animation */
        @keyframes pulse-notification {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .notification-pulse {
            animation: pulse-notification 2s infinite;
        }
        
        /* Hover effects */
        .nav-link-hover {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-link-hover:hover::before {
            left: 100%;
        }
        
        /* Mobile menu slide animation */
        .mobile-menu-slide {
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.3s ease-out;
        }
        
        .mobile-menu-slide.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* Profile avatar border animation */
        .avatar-border {
            position: relative;
        }
        
        .avatar-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6, #6366f1);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .avatar-border:hover::before {
            opacity: 1;
        }
        
        /* Modern profile icon styles */
        .profile-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .profile-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
            opacity: 0;
        }
        
        .profile-icon:hover::before {
            opacity: 1;
            transform: rotate(45deg) translate(30%, 30%);
        }
        
        /* Enhanced dropdown styles */
        .profile-dropdown {
            transform: translateY(-10px) scale(0.95);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .profile-dropdown.show {
            transform: translateY(0) scale(1);
            opacity: 1;
            visibility: visible;
        }
        
        /* Dropdown menu item hover effects */
        .dropdown-item {
            position: relative;
            overflow: hidden;
        }
        
        .dropdown-item::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .dropdown-item:hover::before {
            left: 100%;
        }
        
        /* Profile status indicator */
        .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background: #10b981;
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        
        .status-indicator.pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.1);
            }
        }
            right: -2px;
            bottom: -2px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6, #6366f1);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .avatar-border:hover::before {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Modern Header with Gradient Background -->
    <header class="gradient-animated shadow-lg">
        <nav class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 shadow-md transition-all-smooth hover:shadow-lg hover:scale-105">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white">
                            <span class="hidden sm:inline">Room Booking</span>
                            <span class="sm:hidden">RBS</span>
                        </h1>
                    </div>
                </div>

                <!-- Navigation Links (Desktop) -->
                @auth
                    <div class="hidden md:flex items-center space-x-1">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('admin.rooms') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Rooms
                            </a>
                            <a href="{{ route('admin.bookings') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Bookings
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 15v-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-2"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('rooms.index') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Rooms
                            </a>
                            <a href="{{ route('bookings.index') }}" class="nav-link-hover px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                </svg>
                                My Bookings
                            </a>
                        @endif
                    </div>

                    <!-- Profile Section -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications Bell -->
                        <div class="relative">
                            <button onclick="toggleNotifications()" class="relative p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3-3V9a6 6 0 10-12 0v5l-3 3h5a6 6 0 1012 0z"/>
                                </svg>
                                <!-- Notification Badge -->
                                <span id="notificationBadge" class="notification-pulse absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                            </button>

                            <!-- Notifications Dropdown -->
                            <div id="notificationsDropdown" class="profile-dropdown absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden" style="display: none;">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-white font-bold text-lg">Notifications</h3>
                                        <button onclick="markAllAsRead()" class="text-white hover:text-blue-100 text-sm">Mark all read</button>
                                    </div>
                                </div>
                                <div id="notificationsList" class="max-h-96 overflow-y-auto">
                                    <div class="p-6 text-center text-gray-500">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3-3V9a6 6 0 10-12 0v5l-3 3h5a6 6 0 1012 0z"/>
                                        </svg>
                                        <p>No booking notifications</p>
                                        <p class="text-xs mt-1">You'll see booking updates here</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button type="button"
                                    onclick="toggleProfileDropdown()"
                                    class="flex items-center space-x-3 glass-effect bg-white bg-opacity-10 hover:bg-opacity-20 rounded-xl px-4 py-2 transition-all-smooth focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                                <div class="flex items-center space-x-3">
                                    <!-- Modern Profile Icon -->
                                    <div class="relative">
                                        <div class="profile-icon w-10 h-10 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                        <!-- Online Status Indicator -->
                                        <div class="status-indicator pulse"></div>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                                        <p class="text-blue-100 text-xs capitalize">{{ auth()->user()->role ?? 'User' }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-white transition-transform duration-200" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Enhanced Profile Dropdown -->
                            <div id="profileDropdown"
                                 class="profile-dropdown absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden">
                                
                                <!-- Profile Header -->
                                <div class="bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-600 px-6 py-5 relative overflow-hidden">
                                    <!-- Background decoration -->
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
                                    
                                    <div class="flex items-center space-x-4 relative z-10">
                                        <!-- Profile Icon in Dropdown -->
                                        <div class="relative">
                                            <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center border-2 border-white border-opacity-30">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>
                                            </div>
                                            <!-- Status indicator for dropdown -->
                                            <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-white font-bold text-lg">{{ auth()->user()->name }}</h3>
                                            <p class="text-blue-100 text-sm opacity-90">{{ auth()->user()->email }}</p>
                                            <div class="flex items-center mt-1">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                                <span class="text-white text-xs opacity-75 capitalize">{{ auth()->user()->role ?? 'User' }} • Online</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-3">
                                    <a href="{{ route('profile.show') }}"
                                       class="dropdown-item flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 transition-all duration-200 group">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">My Profile</p>
                                            <p class="text-xs text-gray-500">View and edit your profile</p>
                                        </div>
                                    </a>
                                    
                                    <a href="#" class="dropdown-item flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 transition-all duration-200 group">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Settings</p>
                                            <p class="text-xs text-gray-500">Preferences and configuration</p>
                                        </div>
                                    </a>

                                    <a href="#" class="dropdown-item flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 transition-all duration-200 group">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Help & Support</p>
                                            <p class="text-xs text-gray-500">Get help and contact support</p>
                                        </div>
                                    </a>

                                    <div class="border-t border-gray-100 my-2"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="dropdown-item flex items-center w-full px-6 py-3 text-red-600 hover:bg-red-50 transition-all duration-200 group">
                                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 transition-colors">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                            </div>
                                            <div class="text-left">
                                                <p class="font-medium text-red-700">Sign Out</p>
                                                <p class="text-xs text-red-500">End your current session</p>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-all-smooth">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-100 font-medium transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 py-2 rounded-lg font-medium hover:bg-blue-50 transition-all-smooth shadow-md hover:shadow-lg hover:scale-105">
                            Get Started
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Navigation Menu -->
            @auth
                <div id="mobileMenu" class="hidden md:hidden border-t border-white border-opacity-20 pt-4 pb-4 mobile-menu-slide">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">Dashboard</a>
                        <a href="{{ route('admin.rooms') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">Manage Rooms</a>
                        <a href="{{ route('admin.bookings') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">Manage Bookings</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">Dashboard</a>
                        <a href="{{ route('rooms.index') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">View Rooms</a>
                        <a href="{{ route('bookings.index') }}" class="block px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all-smooth">My Bookings</a>
                    @endif
                </div>
            @endauth
        </nav>
    </header>
    <main>
        @yield('content')
    </main>

    <!-- //footer  -->
    @include('layouts.footer')
    
    <!-- Enhanced JavaScript for Header Interactions -->
    <script>
        // Profile dropdown functionality with enhanced animations
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown.classList.contains('show')) {
                // Hide dropdown
                dropdown.classList.remove('show');
                arrow.classList.remove('rotate-180');
                // Add a slight delay before hiding to complete animation
                setTimeout(() => {
                    if (!dropdown.classList.contains('show')) {
                        dropdown.style.display = 'none';
                    }
                }, 300);
            } else {
                // Show dropdown
                dropdown.style.display = 'block';
                // Force reflow for animation
                dropdown.offsetHeight;
                dropdown.classList.add('show');
                arrow.classList.add('rotate-180');
            }
        }

        // Mobile menu functionality
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('mobile-menu-slide');
            mobileMenu.classList.toggle('show');
        }

        // Notifications functionality
        let notificationsVisible = false;

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            
            if (notificationsVisible) {
                dropdown.style.display = 'none';
                notificationsVisible = false;
            } else {
                dropdown.style.display = 'block';
                notificationsVisible = true;
                loadNotifications();
            }
        }

        function loadNotifications() {
            fetch('/notifications')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(notifications => {
                    const list = document.getElementById('notificationsList');
                    
                    if (notifications.length === 0) {
                        list.innerHTML = `
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p>No booking notifications</p>
                                <p class="text-xs mt-1">You'll see booking updates here</p>
                            </div>
                        `;
                        return;
                    }

                    list.innerHTML = notifications.map(notification => {
                        let iconColor = 'bg-blue-100 text-blue-600';
                        let icon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>`;
                        
                        if (notification.type === 'booking') {
                            if (notification.title.includes('Approved')) {
                                iconColor = 'bg-green-100 text-green-600';
                                icon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>`;
                            } else if (notification.title.includes('Reminder')) {
                                iconColor = 'bg-yellow-100 text-yellow-600';
                                icon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>`;
                            }
                        }

                        return `
                            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 cursor-pointer ${!notification.read_at ? 'bg-blue-50 border-l-4 border-l-blue-500' : ''}" onclick="markAsRead(${notification.id})">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 ${iconColor} rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                ${icon}
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="font-medium text-gray-900">${notification.title}</p>
                                            ${!notification.read_at ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                        <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const list = document.getElementById('notificationsList');
                    list.innerHTML = `
                        <div class="p-6 text-center text-gray-500">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p>Error loading notifications</p>
                        </div>
                    `;
                });
        }

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                updateNotificationCount();
                loadNotifications();
            });
        }

        function markAllAsRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                updateNotificationCount();
                loadNotifications();
            });
        }

        function updateNotificationCount() {
            fetch('/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);

            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            return `${days}d ago`;
        }

        // Load notification count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            
            // Update count every 30 seconds
            setInterval(updateNotificationCount, 30000);
        });

        // Close notifications dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notificationsDropdown');
            const button = dropdown?.previousElementSibling;
            
            if (dropdown && !dropdown.contains(e.target) && !button?.contains(e.target)) {
                if (notificationsVisible) {
                    dropdown.style.display = 'none';
                    notificationsVisible = false;
                }
            }
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const button = dropdown?.previousElementSibling;
            
            if (dropdown && !dropdown.contains(e.target) && !button?.contains(e.target)) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    document.getElementById('dropdownArrow')?.classList.remove('rotate-180');
                    setTimeout(() => {
                        if (!dropdown.classList.contains('show')) {
                            dropdown.style.display = 'none';
                        }
                    }, 300);
                }
            }
            
            // Close mobile menu when clicking outside
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileButton = document.querySelector('[onclick="toggleMobileMenu()"]');
            
            if (mobileMenu && !mobileMenu.contains(e.target) && !mobileButton?.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('show');
            }
        });

        // Add smooth scroll behavior for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states for navigation links
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function() {
                // Add a subtle loading effect
                this.style.opacity = '0.7';
                setTimeout(() => {
                    this.style.opacity = '1';
                }, 200);
            });
        });

        // Initialize dropdown as hidden
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        });

        // Add hover effects for dropdown items
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>
