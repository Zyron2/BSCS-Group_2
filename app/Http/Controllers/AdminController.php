<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;

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
        $room->delete();
        return redirect()->route('admin.rooms')->with('success', 'Room deleted!');
    }

    public function bookings()
    {
        $bookings = Booking::with('room')->orderBy('date', 'desc')->get();
        $rooms = Room::all();
        return view('admin.bookings', compact('bookings', 'rooms'));
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
            'attendees' => 'required|integer'
        ]);
        Booking::create($request->all());
        return redirect()->route('admin.bookings')->with('success', 'Booking added!');
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,rejected,pending'
        ]);
        $booking->status = $request->status;
        $booking->save();
        return redirect()->route('admin.bookings')->with('success', 'Booking status updated!');
    }
}
