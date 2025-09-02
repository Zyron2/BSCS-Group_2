<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard route (shows bookings dashboard)
Route::get('/dashboard', [BookingsController::class, 'index'])->name('dashboard');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Bookings routes
Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');
Route::post('/bookings', [BookingsController::class, 'store'])->name('bookings.store');

// Admin dashboard routes
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
    // Add edit/update/delete for bookings as needed
});

// Routing is already correct for admin and user dashboards