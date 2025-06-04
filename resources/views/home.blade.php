<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Salud Catering</title>
    @vite('resources/css/landing_page.css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    <div class="hero">
        <div class="image-section">
            <img src="{{ asset('./images/background_picture.jpeg') }}" alt="Background" class="background-image">
        </div>
        <div class="content">
            <h1>VILLA SALUD CATERING<br>BOOKING AND RESERVATION SYSTEM</h1>
            <div class="buttons">
                <a href="{{ url('/patron/home') }}" class="btn client">Patrons</a>
                <a href="{{ route('admin.login') }}" class="btn admin">Admin</a>
            </div>
        </div>
    </div>

</body>
</html>
