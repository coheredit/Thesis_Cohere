<!DOCTYPE html>
<html>

<head>
    <title>Reservation Confirmation</title>
</head>

<body>
    <h2>Hi {{ $data['name'] }},</h2>

    <p>Thank you for your reservation inquiry! Here are the details you submitted:</p>

    <ul>
        <li><strong>Reservation Code:</strong> {{ $data['tracking_code'] }}</li>
        <li><strong>Date:</strong> {{ $data['date'] }}</li>
        <li><strong>Time:</strong> {{ $data['time'] }}</li>
        <li><strong>Venue:</strong> {{ $data['venue'] }} {{ $data['other_venue'] ?? '' }}</li>
        <li><strong>Event Type:</strong> {{ $data['event_type'] }} {{ $data['other_event_type'] ?? '' }}</li>
        <li><strong>Theme/Motif:</strong> {{ $data['theme_motif'] }} {{ $data['other_theme_motif'] ?? '' }}</li>
        <li><strong>Message:</strong> {{ $data['message'] }}</li>
    </ul>

    <p>We will get back to you shortly to confirm availability and next steps.</p>

    <p>Best regards,<br>Villa Salud Events Team</p>
</body>

</html>
