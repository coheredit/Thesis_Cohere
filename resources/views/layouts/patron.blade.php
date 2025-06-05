<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Villa Salud Patron')</title>
    <link rel="stylesheet" href="{{ asset('style/patron.css') }}">
    @stack('styles')
</head>

<body>
    <header class="header-image"></header>

    <nav class="navbar">
        <ul>
            <li><a href="{{ route('patron.p_home') }}">Home</a></li>
            <li><a href="{{ route('patron.p_mreserve') }}">Make Reservation</a></li>
            <li><a href="{{ route('patron.p_vreserve') }}">View Reservation</a></li>
            <li><a href="{{ route('patron.p_payment') }}">Payment Order</a></li>
            <li><a href="{{ route('patron.faq') }}">FAQs</a></li>
            <li><a href="{{ route('patron.feedback') }}">Feedback</a></li>

        </ul>
    </nav>

    <main class="patron-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>