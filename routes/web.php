<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\ChatController;
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
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
    });

    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'show'])->name('settings.show');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    
    // Support routes
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'show'])->name('support.show');
    Route::post('/support', [App\Http\Controllers\SupportController::class, 'submit'])->name('support.submit');
    
    // AI Chat Assistant routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/models', [ChatController::class, 'getModels'])->name('chat.models');
    Route::post('/chat/stream', [ChatController::class, 'streamMessage'])->name('chat.stream');
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
    Route::delete('/admin/bookings/{booking}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
});

// Public information pages
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

Route::get('/contactus', function () {
    return view('contactus');
})->name('contactus');

// Remove or comment out test routes in production
// Route::get('/test-notifications', ...);
// Route::get('/debug-notifications', ...);
// Route::get('/test-system', ...);
// Route::get('/create-booking-notification', ...);
