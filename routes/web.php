<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Auth;

// Redirect root to register for new users, or dashboard if authenticated
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('register');
});

// Guest routes (for non-authenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Forgot Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout route (for authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User dashboard routes (for regular users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [BookingsController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingsController::class, 'store'])->name('bookings.store');
    Route::put('/bookings/{booking}', [BookingsController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingsController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/rooms', [BookingsController::class, 'rooms'])->name('rooms.index');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
});

// Admin dashboard routes (for admin users only)
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/rooms', [AdminController::class, 'rooms'])->name('admin.rooms');
    Route::post('/admin/rooms', [AdminController::class, 'storeRoom'])->name('admin.rooms.store');
    Route::get('/admin/rooms/{room}/edit', [AdminController::class, 'editRoom'])->name('admin.rooms.edit');
    Route::post('/admin/rooms/{room}/update', [AdminController::class, 'updateRoom'])->name('admin.rooms.update');
    Route::post('/admin/rooms/{room}/delete', [AdminController::class, 'deleteRoom'])->name('admin.rooms.delete');

    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/admin/bookings', [AdminController::class, 'storeBooking'])->name('admin.bookings.store');
    Route::post('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.status');
});

// Temporary test route - remove after testing
Route::get('/test-notifications', function() {
    if (auth()->check()) {
        // Create some test notifications
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'Booking Confirmed',
            'message' => 'Your room booking for Conference Room A has been confirmed.',
        ]);

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'reminder',
            'title' => 'Upcoming Booking',
            'message' => 'You have a room booking tomorrow at 2:00 PM.',
        ]);

        return 'Test notifications created! Check your notification bell.';
    }
    return 'Please login first';
})->middleware('auth');

// Debug route to check notifications - remove after testing
Route::get('/debug-notifications', function() {
    if (auth()->check()) {
        $notifications = auth()->user()->notifications()->get();
        $count = auth()->user()->notifications()->whereNull('read_at')->count();
        
        return response()->json([
            'user' => auth()->user()->name,
            'total_notifications' => $notifications->count(),
            'unread_count' => $count,
            'notifications' => $notifications
        ]);
    }
    return 'Please login first';
})->middleware('auth');

// Public information pages
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

Route::get('/contactus', function () {
    return view('contactus');
})->name('contactus');

// Simple test route to check if login and notifications work
Route::get('/test-system', function() {
    if (!auth()->check()) {
        return 'Please login first. <a href="/login">Login here</a>';
    }
    
    // Test creating a notification
    try {
        $notification = \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'test',
            'title' => 'System Test',
            'message' => 'This is a test notification to verify the system is working.',
        ]);
        
        return 'Success! Notification created with ID: ' . $notification->id . '. Now check your notification bell icon.';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Test booking notifications
Route::get('/create-booking-notification', function() {
    if (auth()->check()) {
        // Create booking-related notifications
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'New Booking Request',
            'message' => 'You have a new booking request for Conference Room A.',
        ]);

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'Booking Approved',
            'message' => 'Your booking for Meeting Room B has been approved.',
        ]);

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'Booking Reminder',
            'message' => 'Your booking starts in 30 minutes - Conference Room A.',
        ]);

        return 'Booking notifications created! Check your notification bell.';
    }
    return 'Please login first';
})->middleware('auth');
