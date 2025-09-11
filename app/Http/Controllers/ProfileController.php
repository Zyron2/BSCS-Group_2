<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function show()
    {
        return view('profile.show');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Update name
        $user->name = $request->input('name');

        // Handle profile photo upload
        if ($request->hasFile('profile')) {
            // Delete old profile if exists
            if ($user->profile && Storage::disk('public')->exists(str_replace('/storage/', '', $user->profile))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile));
            }

            // Store new profile photo
            $path = $request->file('profile')->store('profiles', 'public');
            $user->profile = '/storage/' . $path;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
