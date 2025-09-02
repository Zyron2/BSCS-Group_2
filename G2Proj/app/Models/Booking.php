<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'organizer',
        'attendees',
        'status'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
