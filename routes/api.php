Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('bookings', BookingController::class);
    Route::get('rooms/{room}/availability', [RoomController::class, 'checkAvailability']);
    Route::get('notifications', [NotificationController::class, 'index']);
});