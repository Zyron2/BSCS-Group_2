<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            \Log::error('Notification fetch error: ' . $e->getMessage());
            return response()->json([], 200);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if ($notification) {
                $notification->update(['read_at' => now()]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            Notification::where('user_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $count = auth()->user()->notifications()
                ->whereNull('read_at')
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Get count error: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }
}
