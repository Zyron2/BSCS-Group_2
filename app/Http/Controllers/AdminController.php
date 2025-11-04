<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Notification;
use App\Mail\BookingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $rooms = Room::all();
        $bookings = Booking::with('room')->orderBy('date', 'desc')->get();
        return view('admin.dashboard', compact('rooms', 'bookings'));
    }

    public function rooms()
    {
        $rooms = Room::all();
        return view('admin.rooms', compact('rooms'));
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|integer',
            'about' => 'nullable|string',
            'equipment' => 'nullable|string'
        ]);
        $equipment = $request->equipment
            ? array_map('trim', explode(',', $request->equipment))
            : [];
        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'about' => $request->about,
            'equipment' => json_encode($equipment)
        ]);
        return redirect()->route('admin.rooms')->with('success', 'Room added!');
    }

    public function editRoom(Room $room)
    {
        return view('admin.edit_room', compact('room'));
    }

    public function updateRoom(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|integer',
            'about' => 'nullable|string',
            'equipment' => 'nullable|string'
        ]);
        $equipment = $request->equipment
            ? array_map('trim', explode(',', $request->equipment))
            : [];
        $room->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'about' => $request->about,
            'equipment' => json_encode($equipment)
        ]);
        return redirect()->route('admin.rooms')->with('success', 'Room updated!');
    }

    public function deleteRoom(Room $room)
    {
        // Get all bookings for this room to notify users
        $affectedBookings = Booking::where('room_id', $room->id)
            ->with('user')
            ->get();

        // Create notifications for users with bookings in this room
        foreach ($affectedBookings as $booking) {
            Notification::create([
                'user_id' => $booking->user_id,
                'type' => 'booking',
                'title' => 'Room Deleted - Booking Cancelled',
                'message' => "The room '{$room->name}' has been deleted. Your booking '{$booking->title}' on {$booking->date} has been automatically cancelled.",
            ]);
        }

        // Delete all bookings associated with this room (cascade delete)
        Booking::where('room_id', $room->id)->delete();

        // Delete the room
        $room->delete();

        return redirect()->route('admin.rooms')->with('success', 'Room and all associated bookings deleted successfully. Affected users have been notified.');
    }

    public function bookings(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $search = $request->input('search', '');
        $roomId = $request->input('room_id', '');

        $query = Booking::with(['room', 'user'])->where('date', $date);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('organizer', 'like', "%$search%");
            });
        }

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        $bookings = $query->orderBy('start_time')->get();
        $rooms = Room::all();

        return view('admin.bookings', compact('bookings', 'rooms', 'date', 'search', 'roomId'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'organizer' => 'required',
            'attendees' => 'required|integer',
            'user_id' => 'required|exists:users,id'
        ]);

        $booking = Booking::create(array_merge($request->all(), [
            'status' => 'confirmed' // Admin bookings are automatically confirmed
        ]));

        // Notify the user
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'booking',
            'title' => 'Booking Created by Admin',
            'message' => "A booking '{$booking->title}' has been created for you on {$booking->date} at {$booking->start_time}.",
        ]);

        return redirect()->route('admin.bookings')->with('success', 'Booking created successfully!');
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,rejected']);
        
        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        // Create notification for user
        $message = match($request->status) {
            'confirmed' => "Your booking for {$booking->room->name} on {$booking->date} has been confirmed.",
            'rejected' => "Your booking for {$booking->room->name} on {$booking->date} has been rejected.",
            'pending' => "Your booking for {$booking->room->name} on {$booking->date} status changed to pending.",
        };

        \App\Models\Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking',
            'title' => 'Booking Status Updated',
            'message' => $message,
        ]);

        // Send email notification if user has email notifications enabled
        $user = $booking->user;
        $settings = $user->settings ?? [];
        
        if (isset($settings['email_notifications']) && $settings['email_notifications'] && $oldStatus !== $request->status) {
            try {
                Mail::to($user->email)->send(new BookingNotification($booking, $request->status));
            } catch (\Exception $e) {
                \Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    // Add method to delete bookings from admin panel
    public function deleteBooking(Booking $booking)
    {
        $roomName = $booking->room->name;
        $bookingTitle = $booking->title;
        $bookingDate = $booking->date;
        $userId = $booking->user_id;

        // Only send notification if booking has a user
        if ($userId) {
            // Create notification for the user
            \App\Models\Notification::create([
                'user_id' => $userId,
                'type' => 'booking',
                'title' => 'Booking Cancelled by Admin',
                'message' => "Your booking '{$bookingTitle}' for {$roomName} on {$bookingDate} has been cancelled by an administrator.",
            ]);

            // Send email notification if user has email notifications enabled
            $user = $booking->user;
            if ($user) {
                $settings = $user->settings ?? [];
                
                if (isset($settings['email_notifications']) && $settings['email_notifications']) {
                    try {
                        Mail::to($user->email)->send(new BookingNotification($booking, 'cancelled'));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send deletion email: ' . $e->getMessage());
                    }
                }
            }
        }

        // Delete the booking
        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }
}
