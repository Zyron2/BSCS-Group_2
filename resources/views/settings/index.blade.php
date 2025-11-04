@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Settings</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your preferences and account settings</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <!-- Appearance Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        Appearance
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Dark Mode with Animated Switch -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                        <div class="flex-1">
                            <label class="text-base font-semibold text-gray-900 dark:text-gray-100 flex items-center cursor-pointer" for="darkModeToggle">
                                <div class="relative mr-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg id="darkModeIcon" class="w-6 h-6 text-white transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-gray-900 dark:text-gray-100 font-semibold">Dark Mode</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Switch between light and dark themes</div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Animated Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   id="darkModeToggle"
                                   name="dark_mode" 
                                   value="1" 
                                   class="sr-only toggle-checkbox" 
                                   {{ isset($settings['dark_mode']) && $settings['dark_mode'] ? 'checked' : '' }} 
                                   onchange="toggleDarkMode(this)">
                            
                            <!-- Toggle Background -->
                            <div class="toggle-bg w-20 h-10 bg-gray-300 dark:bg-gray-600 rounded-full shadow-lg transition-all duration-500 relative overflow-hidden group-hover:shadow-xl border-2 border-gray-400 dark:border-gray-500">
                                
                                <!-- Stars Animation (appears in dark mode) -->
                                <div class="stars-container absolute inset-0 opacity-0 transition-opacity duration-500">
                                    <div class="absolute top-2 left-4 w-1 h-1 bg-white rounded-full animate-pulse"></div>
                                    <div class="absolute top-5 left-7 w-1 h-1 bg-white rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                    <div class="absolute top-3 left-10 w-1 h-1 bg-white rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                                </div>
                                
                                <!-- Toggle Circle/Button -->
                                <div class="toggle-circle absolute top-1 left-1 bg-white w-8 h-8 rounded-full shadow-md flex items-center justify-center transition-all duration-500 border-2 border-gray-300 dark:border-gray-400">
                                    <!-- Sun Icon (Light Mode) -->
                                    <svg class="sun-icon w-5 h-5 text-yellow-500 absolute transition-all duration-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                                    </svg>
                                    
                                    <!-- Moon Icon (Dark Mode) -->
                                    <svg class="moon-icon w-5 h-5 text-indigo-600 absolute opacity-0 transition-all duration-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            Language
                        </label>
                        <select name="language" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="en" {{ isset($settings['language']) && $settings['language'] == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ isset($settings['language']) && $settings['language'] == 'es' ? 'selected' : '' }}>Español</option>
                            <option value="fr" {{ isset($settings['language']) && $settings['language'] == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="de" {{ isset($settings['language']) && $settings['language'] == 'de' ? 'selected' : '' }}>Deutsch</option>
                        </select>
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date Format
                        </label>
                        <select name="date_format" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="Y-m-d" {{ isset($settings['date_format']) && $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-01-15)</option>
                            <option value="m/d/Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2024)</option>
                            <option value="d/m/Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2024)</option>
                            <option value="M d, Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'M d, Y' ? 'selected' : '' }}>Mon DD, YYYY (Jan 15, 2024)</option>
                        </select>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Time Format
                        </label>
                        <select name="time_format" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="24h" {{ isset($settings['time_format']) && $settings['time_format'] == '24h' ? 'selected' : '' }}>24-hour (14:30)</option>
                            <option value="12h" {{ isset($settings['time_format']) && $settings['time_format'] == '12h' ? 'selected' : '' }}>12-hour (2:30 PM)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-600">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifications
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Email Notifications -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email Notifications
                            </label>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Receive email updates about your bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   name="email_notifications" 
                                   value="1" 
                                   class="sr-only notification-toggle" 
                                   {{ isset($settings['email_notifications']) && $settings['email_notifications'] ? 'checked' : '' }}>
                            <div class="notification-toggle-bg w-16 h-8 bg-gray-300 dark:bg-gray-600 rounded-full shadow-lg transition-all duration-300 group-hover:shadow-xl border-2 border-gray-400 dark:border-gray-500">
                                <div class="notification-toggle-circle absolute top-0.5 left-0.5 bg-white w-7 h-7 rounded-full shadow-md flex items-center justify-center transition-transform duration-300 border-2 border-gray-300 dark:border-gray-400">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Booking Reminders -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Booking Reminders
                            </label>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get reminders 1 day before your scheduled bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   name="booking_reminders" 
                                   value="1" 
                                   class="sr-only notification-toggle" 
                                   {{ isset($settings['booking_reminders']) && $settings['booking_reminders'] ? 'checked' : '' }}>
                            <div class="notification-toggle-bg w-16 h-8 bg-gray-300 dark:bg-gray-600 rounded-full shadow-lg transition-all duration-300 group-hover:shadow-xl border-2 border-gray-400 dark:border-gray-500">
                                <div class="notification-toggle-circle absolute top-0.5 left-0.5 bg-white w-7 h-7 rounded-full shadow-md flex items-center justify-center transition-transform duration-300 border-2 border-gray-300 dark:border-gray-400">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between">
                <a href="{{ route('dashboard') }}" class="bg-gray-600 dark:bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition duration-200 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200 font-medium shadow-lg">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Toggle switch when checked */
.toggle-checkbox:checked ~ .toggle-bg {
    background: linear-gradient(to right, #4f46e5, #7c3aed);
    border-color: #6366f1;
}

.toggle-checkbox:checked ~ .toggle-bg .toggle-circle {
    transform: translateX(2.5rem); /* 40px slide to the right */
    border-color: #8b5cf6;
}

.toggle-checkbox:checked ~ .toggle-bg .stars-container {
    opacity: 1;
}

.toggle-checkbox:checked ~ .toggle-bg .sun-icon {
    opacity: 0;
    transform: rotate(180deg) scale(0);
}

.toggle-checkbox:checked ~ .toggle-bg .moon-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

.toggle-checkbox:focus ~ .toggle-bg {
    ring: 4px;
    ring-color: rgba(139, 92, 246, 0.3);
    border-color: #8b5cf6;
}

/* Moon icon initial state */
.moon-icon {
    transform: rotate(-180deg) scale(0);
}

/* Smooth icon rotation */
#darkModeIcon {
    transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.dark #darkModeIcon {
    transform: rotate(360deg);
}

/* Glowing effect on hover */
.group:hover .toggle-bg {
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
    border-color: #a78bfa;
}

/* Dark mode specific for main toggle */
.dark .toggle-checkbox:checked ~ .toggle-bg {
    background: linear-gradient(to right, #6366f1, #8b5cf6);
    border-color: #a78bfa;
}

.dark .toggle-bg {
    border-color: #6b7280;
}

.dark .toggle-circle {
    border-color: #6b7280;
}

.dark .toggle-checkbox:checked ~ .toggle-bg .toggle-circle {
    border-color: #a78bfa;
}

/* Notification toggle switches - Enhanced visibility */
.notification-toggle:checked ~ .notification-toggle-bg {
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    border-color: #2563eb;
}

.notification-toggle:checked ~ .notification-toggle-bg .notification-toggle-circle {
    transform: translateX(2rem);
    background: white;
    border-color: #3b82f6;
}

.notification-toggle:checked ~ .notification-toggle-bg svg {
    color: #3b82f6;
}

.notification-toggle:focus ~ .notification-toggle-bg {
    ring: 4px;
    ring-color: rgba(59, 130, 246, 0.3);
    border-color: #3b82f6;
}

/* Notification toggle background */
.notification-toggle-bg {
    position: relative;
}

.notification-toggle-circle {
    transition: transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

.group:hover .notification-toggle-bg {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    border-color: #60a5fa;
}

/* Dark mode specific for notification toggles */
.dark .notification-toggle:checked ~ .notification-toggle-bg {
    background: linear-gradient(to right, #2563eb, #0891b2);
    border-color: #3b82f6;
}

.dark .notification-toggle-bg {
    border-color: #6b7280;
}

.dark .notification-toggle-circle {
    border-color: #6b7280;
}

.dark .notification-toggle:checked ~ .notification-toggle-bg .notification-toggle-circle {
    border-color: #60a5fa;
}
</style>

<script>
function toggleDarkMode(checkbox) {
    const icon = document.getElementById('darkModeIcon');
    
    if (checkbox.checked) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'enabled');
        
        // Change to moon icon
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
        icon.style.transform = 'rotate(360deg) scale(1.1)';
        setTimeout(() => {
            icon.style.transform = 'rotate(360deg) scale(1)';
        }, 300);
        
        // Show toast notification
        showToast('🌙 Dark mode enabled', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'disabled');
        
        // Change to sun icon
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
        icon.style.transform = 'rotate(0deg) scale(1.1)';
        setTimeout(() => {
            icon.style.transform = 'rotate(0deg) scale(1)';
        }, 300);
        
        // Show toast notification
        showToast('☀️ Light mode enabled', 'light');
    }
}

function showToast(message, theme) {
    const toast = document.createElement('div');
    const bgColor = theme === 'dark' ? 'bg-gray-800' : 'bg-white';
    const textColor = theme === 'dark' ? 'text-white' : 'text-gray-800';
    const borderColor = theme === 'dark' ? 'border-gray-700' : 'border-gray-200';
    
    toast.className = `fixed bottom-4 right-4 ${bgColor} ${textColor} px-6 py-4 rounded-xl shadow-2xl z-50 transition-all duration-300 transform translate-y-0 opacity-100 border ${borderColor}`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                ${theme === 'dark' ? 
                    '<div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg></div>' :
                    '<div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg></div>'
                }
            </div>
            <div>
                <p class="font-semibold">${message}</p>
                <p class="text-xs opacity-75 mt-1">Theme preference saved</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateY(0) scale(1)';
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px) scale(0.9)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load dark mode preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const darkModeCheckbox = document.querySelector('input[name="dark_mode"]');
    const icon = document.getElementById('darkModeIcon');
    
    // Check if dark mode is enabled in localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled' || 
                       document.documentElement.classList.contains('dark');
    
    if (darkModeCheckbox) {
        darkModeCheckbox.checked = isDarkMode;
        
        // Apply dark mode if enabled
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            if (icon) {
                // Set moon icon for dark mode
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
                icon.style.transform = 'rotate(360deg)';
            }
        } else {
            // Set sun icon for light mode
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    }
});
</script>
@endsection
