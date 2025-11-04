<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $settings = $user->settings ?? $this->getDefaultSettings();
        
        // Ensure dark_mode is a boolean
        if (isset($settings['dark_mode'])) {
            $settings['dark_mode'] = (bool) $settings['dark_mode'];
        }
        
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $settings = [
            'dark_mode' => $request->boolean('dark_mode'),
            'email_notifications' => $request->boolean('email_notifications'),
            'booking_reminders' => $request->boolean('booking_reminders'),
            'language' => $request->input('language', 'en'),
            'date_format' => $request->input('date_format', 'Y-m-d'),
            'time_format' => $request->input('time_format', '24h'),
        ];

        $user->update(['settings' => $settings]);
        
        // Set response to include dark mode in session for immediate feedback
        session()->flash('dark_mode', $settings['dark_mode']);

        return redirect()->route('settings.show')->with('success', 'Settings updated successfully!');
    }

    private function getDefaultSettings()
    {
        return [
            'dark_mode' => false,
            'email_notifications' => true,
            'booking_reminders' => true,
            'language' => 'en',
            'date_format' => 'Y-m-d',
            'time_format' => '24h',
        ];
    }
}
