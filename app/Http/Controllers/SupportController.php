<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function show()
    {
        return view('support.index');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'message' => 'required|string|min:10',
        ]);

        // Create notification for admin or send email
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type' => 'support',
            'title' => 'Support Request Submitted',
            'message' => "Your support request '{$request->subject}' has been submitted. We'll respond within 24 hours.",
        ]);

        // Here you could also send email to support team
        // Mail::to('support@yourapp.com')->send(new SupportRequest($request->all()));

        return redirect()->route('support.show')->with('success', 'Support request submitted successfully! We will get back to you soon.');
    }
}
