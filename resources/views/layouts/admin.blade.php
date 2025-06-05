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

    {{-- Header image from the correct design --}}
    <header class="header-image">
        <img src="{{ asset('images/background_picture.jpeg') }}" alt="Header" class="header-img">
    </header>

    {{-- Navbar from the correct design with route-aware active class --}}
    <nav class="navbar">
        <ul>
            <li><a href="{{ route('admin.home') }}" class="{{ request()->is('admin/home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('admin.inquiry') }}" class="{{ request()->is('admin/inquiry') ? 'active' : '' }}">Inquiries</a></li>
            <li><a href="{{ route('admin.reserve') }}" class="{{ request()->is('admin/reserver') ? 'active' : '' }}">Reservations</a></li>
            <li><a href="{{ route('admin.report') }}" class="{{ request()->is('admin/report') ? 'active' : '' }}">Reports</a></li>
            <li><a href="{{ route('admin.profile') }}" class="{{ request()->is('admin/profile') ? 'active' : '' }}">Admin Profile</a></li>
        </ul>
    </nav>

    <main class="admin-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
