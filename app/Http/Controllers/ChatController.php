<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     */
    public function index()
    {
        return view('chat.index');
    }

    /**
     * Send message to Ollama and get response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'model' => 'string|nullable'
        ]);

        $message = $request->input('message');
        $model = $request->input('model', 'llama3.2:1b'); // Default model

        try {
            // Get context about the app's data
            $context = $this->getAppContext($message);
            
            // Build the enhanced prompt with context
            $enhancedPrompt = $this->buildPromptWithContext($message, $context);
            
            // Ollama API endpoint (default: http://localhost:11434)
            $ollamaUrl = env('OLLAMA_API_URL', 'http://localhost:11434');
            
            // Make request to Ollama API
            $response = Http::timeout(120)->post("{$ollamaUrl}/api/generate", [
                'model' => $model,
                'prompt' => $enhancedPrompt,
                'stream' => false
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'success' => true,
                    'response' => $data['response'] ?? 'No response from AI',
                    'model' => $model,
                    'created_at' => now()->toIso8601String()
                ]);
            } else {
                throw new \Exception('Ollama API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to get response from AI assistant. Make sure Ollama is running.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available models from Ollama
     */
    public function getModels()
    {
        try {
            $ollamaUrl = env('OLLAMA_API_URL', 'http://localhost:11434');
            
            $response = Http::timeout(10)->get("{$ollamaUrl}/api/tags");

            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'success' => true,
                    'models' => $data['models'] ?? []
                ]);
            } else {
                throw new \Exception('Failed to fetch models');
            }

        } catch (\Exception $e) {
            Log::error('Get Models Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Could not fetch available models',
                'models' => []
            ], 500);
        }
    }

    /**
     * Stream chat response (for real-time streaming)
     */
    public function streamMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'model' => 'string|nullable'
        ]);

        $message = $request->input('message');
        $model = $request->input('model', 'llama3.2:1b');

        return response()->stream(function () use ($message, $model) {
            $ollamaUrl = env('OLLAMA_API_URL', 'http://localhost:11434');
            
            try {
                $response = Http::withOptions([
                    'stream' => true,
                    'timeout' => 120
                ])->post("{$ollamaUrl}/api/generate", [
                    'model' => $model,
                    'prompt' => $message,
                    'stream' => true
                ]);

                foreach ($response as $chunk) {
                    echo "data: " . $chunk . "\n\n";
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
            } catch (\Exception $e) {
                echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no'
        ]);
    }

    /**
     * Get relevant app context based on user message
     */
    private function getAppContext($message)
    {
        $context = [];
        $messageLower = strtolower($message);
        
        // Check if user is asking about rooms
        if (preg_match('/\b(room|rooms|available|book|capacity|equipment)\b/i', $message)) {
            $rooms = Room::all();
            $context['rooms'] = $rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'capacity' => $room->capacity,
                    'about' => $room->about,
                    'equipment' => $room->equipment,
                    'available' => $this->isRoomAvailable($room->id)
                ];
            })->toArray();
        }
        
        // Check if user is asking about bookings
        if (preg_match('/\b(booking|bookings|schedule|reserved|appointment|meeting)\b/i', $message)) {
            $user = Auth::user();
            
            // Get user's bookings
            $userBookings = Booking::where('user_id', $user->id)
                ->with('room')
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();
            
            $context['my_bookings'] = $userBookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'room' => $booking->room->name ?? 'Unknown',
                    'title' => $booking->title,
                    'date' => $booking->date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'status' => $booking->status,
                    'organizer' => $booking->organizer,
                    'attendees' => $booking->attendees
                ];
            })->toArray();
            
            // Get today's bookings for all rooms
            $todayBookings = Booking::whereDate('date', today())
                ->with('room', 'user')
                ->get();
            
            $context['todays_bookings'] = $todayBookings->map(function($booking) {
                return [
                    'room' => $booking->room->name ?? 'Unknown',
                    'title' => $booking->title,
                    'time' => $booking->start_time . ' - ' . $booking->end_time,
                    'status' => $booking->status
                ];
            })->toArray();
        }
        
        // Get user information
        $user = Auth::user();
        $context['current_user'] = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'user'
        ];
        
        return $context;
    }

    /**
     * Check if a room is available (not booked for the next hour)
     */
    private function isRoomAvailable($roomId)
    {
        $now = now();
        $oneHourLater = $now->copy()->addHour();
        
        $hasBooking = Booking::where('room_id', $roomId)
            ->whereDate('date', $now->toDateString())
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($now, $oneHourLater) {
                $query->where(function($q) use ($now, $oneHourLater) {
                    $q->where('start_time', '<=', $now->format('H:i:s'))
                      ->where('end_time', '>=', $now->format('H:i:s'));
                })
                ->orWhere(function($q) use ($now, $oneHourLater) {
                    $q->where('start_time', '<=', $oneHourLater->format('H:i:s'))
                      ->where('end_time', '>=', $oneHourLater->format('H:i:s'));
                });
            })
            ->exists();
        
        return !$hasBooking;
    }

    /**
     * Build enhanced prompt with app context
     */
    private function buildPromptWithContext($userMessage, $context)
    {
        $systemPrompt = "You are an AI assistant for Campus Coord, a room booking system. ";
        $systemPrompt .= "You have access to real-time data from the application. ";
        $systemPrompt .= "Answer questions accurately based on the provided context. ";
        $systemPrompt .= "Be helpful, concise, and friendly. ";
        $systemPrompt .= "If asked about rooms, bookings, or schedules, use the provided data.\n\n";
        
        // Add current user context
        if (isset($context['current_user'])) {
            $user = $context['current_user'];
            $systemPrompt .= "Current User: {$user['name']} ({$user['email']}) - Role: {$user['role']}\n\n";
        }
        
        // Add rooms context
        if (isset($context['rooms']) && !empty($context['rooms'])) {
            $systemPrompt .= "AVAILABLE ROOMS:\n";
            foreach ($context['rooms'] as $room) {
                $availability = $room['available'] ? 'Available now' : 'Currently booked';
                $equipment = is_array($room['equipment']) ? implode(', ', $room['equipment']) : $room['equipment'];
                $systemPrompt .= "- {$room['name']}: Capacity {$room['capacity']} people, {$availability}";
                if ($room['about']) {
                    $systemPrompt .= ", About: {$room['about']}";
                }
                if ($equipment) {
                    $systemPrompt .= ", Equipment: {$equipment}";
                }
                $systemPrompt .= "\n";
            }
            $systemPrompt .= "\n";
        }
        
        // Add user's bookings context
        if (isset($context['my_bookings']) && !empty($context['my_bookings'])) {
            $systemPrompt .= "USER'S BOOKINGS:\n";
            foreach ($context['my_bookings'] as $booking) {
                $systemPrompt .= "- {$booking['title']} in {$booking['room']} on {$booking['date']} ";
                $systemPrompt .= "from {$booking['start_time']} to {$booking['end_time']} ";
                $systemPrompt .= "({$booking['status']})\n";
            }
            $systemPrompt .= "\n";
        }
        
        // Add today's bookings context
        if (isset($context['todays_bookings']) && !empty($context['todays_bookings'])) {
            $systemPrompt .= "TODAY'S BOOKINGS (All Rooms):\n";
            foreach ($context['todays_bookings'] as $booking) {
                $systemPrompt .= "- {$booking['room']}: {$booking['title']} at {$booking['time']} ({$booking['status']})\n";
            }
            $systemPrompt .= "\n";
        }
        
        $systemPrompt .= "User Question: {$userMessage}\n\n";
        $systemPrompt .= "Please provide a helpful answer based on the above information:";
        
        return $systemPrompt;
    }
}
