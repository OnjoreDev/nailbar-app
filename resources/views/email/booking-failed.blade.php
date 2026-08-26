<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Fully Booked</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Hello {{ $name }},</h2>
    <p>We're sorry, but the date you requested (<strong>{{ $bookingDate }}</strong>) is already fully booked (maximum 10 clients per day).</p>
    
    <p>Please visit our booking page again to select another available date.</p>
    
    <p>Thank you for understanding!</p>
</body>
</html>