<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-label {
            font-weight: bold;
            width: 140px;
            color: #6b7280;
        }
        .detail-value {
            color: #1f2937;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Campus Coord</h1>
        <p style="margin: 0; opacity: 0.9;">Room Booking System</p>
    </div>

    <div class="content">
        @if($type === 'created')
            <h2>Booking Request Submitted</h2>
            <p>Hi {{ $booking->user->name }},</p>
            <p>Your room booking request has been submitted successfully and is pending admin approval.</p>
        @elseif($type === 'confirmed')
            <h2>Booking Confirmed! ✓</h2>
            <p>Hi {{ $booking->user->name }},</p>
            <p>Great news! Your room booking has been <strong>confirmed</strong>.</p>
        @elseif($type === 'rejected')
            <h2>Booking Update</h2>
            <p>Hi {{ $booking->user->name }},</p>
            <p>We regret to inform you that your booking request has been declined.</p>
        @elseif($type === 'cancelled')
            <h2>Booking Cancelled</h2>
            <p>Hi {{ $booking->user->name }},</p>
            <p>Your room booking has been cancelled.</p>
        @elseif($type === 'reminder')
            <h2>Booking Reminder ⏰</h2>
            <p>Hi {{ $booking->user->name }},</p>
            <p>This is a friendly reminder about your upcoming room booking:</p>
        @endif

        <div class="booking-details">
            <h3 style="margin-top: 0; color: #1f2937;">Booking Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Event Title:</span>
                <span class="detail-value">{{ $booking->title }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Room:</span>
                <span class="detail-value">{{ $booking->room->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->date)->format('l, F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span class="detail-value">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Organizer:</span>
                <span class="detail-value">{{ $booking->organizer }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Attendees:</span>
                <span class="detail-value">{{ $booking->attendees }} people</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </span>
            </div>
        </div>

        @if($type === 'reminder')
            <p><strong>Your booking is scheduled for tomorrow!</strong> Please make sure you're prepared.</p>
        @endif

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ url('/bookings') }}" class="button">View My Bookings</a>
        </div>

        <p style="color: #6b7280; font-size: 14px;">
            If you have any questions, please contact support or check the help section in your dashboard.
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Campus Coord. All rights reserved.</p>
        <p style="font-size: 12px; margin: 5px 0;">
            This email was sent because you have email notifications enabled in your settings.<br>
            You can manage your notification preferences in <a href="{{ url('/settings') }}">Settings</a>.
        </p>
    </div>
</body>
</html>
