<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    <header class="header-image">
        <img src="{{ asset('images/background_picture.jpeg') }}" alt="Header Image" class="header-img">
    </header>

    </header>

    <nav class="navbar">
        <ul>
            <li><a href="{{ route('admin.home') }}">Home</a></li>
            <li><a href="{{ route('admin.inquiry') }}">View Inquiries</a></li>
            <li><a href="{{ route('admin.reserve') }}">Make Reservation</a></li>
            <li><a href="{{ route('admin.report') }}">View Report</a></li>
            <li><a href="{{ route('admin.profile') }}">Admin Profile</a></li>
        </ul>
    </nav>

    <main class="admin-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
