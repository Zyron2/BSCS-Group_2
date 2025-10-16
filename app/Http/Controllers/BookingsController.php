<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        // Check if this is specifically the /bookings route (user's personal bookings)
        if ($request->route()->getName() === 'bookings.index') {
            // This is user's personal bookings view
            $bookings = Booking::with('room')
                ->where('user_id', auth()->id())
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();

            return view('bookings.index', compact('bookings'));
        } else {
            // This is dashboard view - show all bookings for today with filters
            $date = $request->input('date', date('Y-m-d'));
            $search = $request->input('search', '');
            $roomId = $request->input('room_id', '');

            $query = Booking::with('room')->where('date', $date);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('organizer', 'like', "%$search%");
                });
            }

            if ($roomId) {
                $query->where('room_id', $roomId);
            }

            $bookings = $query->get();
            $rooms = Room::all();

            // Pass the selected room ID to pre-select it in the form
            $selectedRoomId = $roomId;

            return view('bookings.dashboard', compact('bookings', 'rooms', 'date', 'search', 'roomId', 'selectedRoomId'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'organizer' => 'required',
            'attendees' => 'required|integer'
        ]);

        // Create notification for new booking
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'Booking Created',
            'message' => 'Your booking request for ' . Room::find($request->room_id)->name . ' has been submitted.',
        ]);

        Booking::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]));

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function update(Request $request, Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'You can only update your own bookings.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'organizer' => 'required|string|max:255',
            'attendees' => 'required|integer|min:1',
        ]);

        $booking->update($request->only([
            'room_id', 'title', 'date', 'start_time', 'end_time', 'organizer', 'attendees'
        ]));

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'You can only cancel your own bookings.');
        }

        // Check if the booking can still be cancelled (e.g., not in the past)
        $bookingDateTime = \Carbon\Carbon::parse($booking->date . ' ' . $booking->start_time);
        if ($bookingDateTime->isPast()) {
            return redirect()->route('bookings.index')->with('error', 'Cannot cancel past bookings.');
        }

        // Store room name before deletion
        $roomName = $booking->room->name;
        $bookingDate = $booking->date;

        // Create a notification for the cancellation
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'booking',
            'title' => 'Booking Cancelled',
            'message' => 'Your booking for ' . $roomName . ' on ' . $bookingDate . ' has been cancelled.',
        ]);

        // Delete the booking
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }

    public function rooms()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }
}
