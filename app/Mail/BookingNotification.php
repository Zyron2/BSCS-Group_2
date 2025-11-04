<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;

    public function __construct(Booking $booking, $type = 'created')
    {
        $this->booking = $booking;
        $this->type = $type; // created, confirmed, rejected, cancelled, reminder
    }

    public function build()
    {
        $subject = match($this->type) {
            'created' => 'Booking Request Submitted',
            'confirmed' => 'Booking Confirmed',
            'rejected' => 'Booking Rejected',
            'cancelled' => 'Booking Cancelled',
            'reminder' => 'Booking Reminder',
            default => 'Booking Update'
        };

        return $this->subject($subject)
                    ->view('emails.booking-notification')
                    ->with([
                        'booking' => $this->booking,
                        'type' => $this->type
                    ]);
    }
}
