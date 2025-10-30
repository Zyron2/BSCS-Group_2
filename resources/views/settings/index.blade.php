@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600 mt-2">Manage your preferences and account settings</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <!-- Appearance Settings -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        Appearance
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Dark Mode -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                Dark Mode
                            </label>
                            <p class="text-sm text-gray-500 mt-1">Use dark theme across the application</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="dark_mode" value="1" class="sr-only peer" {{ isset($settings['dark_mode']) && $settings['dark_mode'] ? 'checked' : '' }} onchange="toggleDarkMode(this)">
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            Language
                        </label>
                        <select name="language" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="en" {{ isset($settings['language']) && $settings['language'] == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ isset($settings['language']) && $settings['language'] == 'es' ? 'selected' : '' }}>Español</option>
                            <option value="fr" {{ isset($settings['language']) && $settings['language'] == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="de" {{ isset($settings['language']) && $settings['language'] == 'de' ? 'selected' : '' }}>Deutsch</option>
                        </select>
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date Format
                        </label>
                        <select name="date_format" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Y-m-d" {{ isset($settings['date_format']) && $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-01-15)</option>
                            <option value="m/d/Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2024)</option>
                            <option value="d/m/Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2024)</option>
                            <option value="M d, Y" {{ isset($settings['date_format']) && $settings['date_format'] == 'M d, Y' ? 'selected' : '' }}>Mon DD, YYYY (Jan 15, 2024)</option>
                        </select>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Time Format
                        </label>
                        <select name="time_format" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="24h" {{ isset($settings['time_format']) && $settings['time_format'] == '24h' ? 'selected' : '' }}>24-hour (14:30)</option>
                            <option value="12h" {{ isset($settings['time_format']) && $settings['time_format'] == '12h' ? 'selected' : '' }}>12-hour (2:30 PM)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
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
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email Notifications
                            </label>
                            <p class="text-sm text-gray-500 mt-1">Receive email updates about your bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ isset($settings['email_notifications']) && $settings['email_notifications'] ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Booking Reminders -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Booking Reminders
                            </label>
                            <p class="text-sm text-gray-500 mt-1">Get reminders before your scheduled bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="booking_reminders" value="1" class="sr-only peer" {{ isset($settings['booking_reminders']) && $settings['booking_reminders'] ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Timezone
                        </label>
                        <select name="timezone" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="UTC" {{ isset($settings['timezone']) && $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                            <option value="America/New_York" {{ isset($settings['timezone']) && $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time (GMT-5)</option>
                            <option value="America/Chicago" {{ isset($settings['timezone']) && $settings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time (GMT-6)</option>
                            <option value="America/Denver" {{ isset($settings['timezone']) && $settings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time (GMT-7)</option>
                            <option value="America/Los_Angeles" {{ isset($settings['timezone']) && $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (GMT-8)</option>
                            <option value="Europe/London" {{ isset($settings['timezone']) && $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>London (GMT+0)</option>
                            <option value="Europe/Paris" {{ isset($settings['timezone']) && $settings['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Paris (GMT+1)</option>
                            <option value="Asia/Tokyo" {{ isset($settings['timezone']) && $settings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo (GMT+9)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between">
                <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition duration-200 font-medium shadow-lg">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDarkMode(checkbox) {
    if (checkbox.checked) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'enabled');
        
        // Visual feedback
        showToast('Dark mode enabled');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'disabled');
        
        // Visual feedback
        showToast('Light mode enabled');
    }
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// Load dark mode preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const darkModeCheckbox = document.querySelector('input[name="dark_mode"]');
    
    // Check if dark mode is enabled in localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled' || 
                       document.documentElement.classList.contains('dark');
    
    if (darkModeCheckbox) {
        darkModeCheckbox.checked = isDarkMode;
        
        // Apply dark mode if enabled
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        }
    }
});
</script>
@endsection
