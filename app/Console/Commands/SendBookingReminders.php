<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use App\Mail\BookingNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send booking reminders to users for bookings happening tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        // Get all bookings for tomorrow with confirmed status
        $bookings = Booking::with(['user', 'room'])
            ->where('date', $tomorrow)
            ->where('status', 'confirmed')
            ->get();

        $count = 0;

        foreach ($bookings as $booking) {
            $user = $booking->user;
            $settings = $user->settings ?? [];

            // Check if user has booking reminders enabled
            if (isset($settings['booking_reminders']) && $settings['booking_reminders']) {
                
                // Create in-app notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'booking',
                    'title' => 'Booking Reminder',
                    'message' => "Reminder: Your booking for {$booking->room->name} is tomorrow at {$booking->start_time}."
                ]);

                // Send email if email notifications are enabled
                if (isset($settings['email_notifications']) && $settings['email_notifications']) {
                    try {
                        Mail::to($user->email)->send(new BookingNotification($booking, 'reminder'));
                    } catch (\Exception $e) {
                        $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
                    }
                }

                $count++;
            }
        }

        $this->info("Sent {$count} booking reminders.");
        return 0;
    }
}
