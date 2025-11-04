<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SendBookingReminders::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Send booking reminders every day at 6 PM
        $schedule->command('bookings:send-reminders')
                 ->dailyAt('18:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
