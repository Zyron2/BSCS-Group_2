<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
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

        return view('bookings.dashboard', compact('bookings', 'rooms', 'date', 'search', 'roomId'));
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

        Booking::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]));

        return redirect()->route('bookings.index')->with('success', 'Booking created!');
    }

    public function rooms()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    // Add edit, update, destroy methods as needed
}
