<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Hello {{ $booking->name }},</h2>
    <p>Good news! Your nail appointment has been <strong>successfully confirmed</strong>.</p>
    
    <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <p><strong>Appointment Date:</strong> {{ $booking->booking_date->format('F j, Y') }}</p>
    </div>

    <p>Thank you for choosing our services!</p>
</body>
</html>