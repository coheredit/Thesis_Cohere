<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Villa Salud Patron')</title>

    @vite([]) {{-- Add this to initialize Vite --}}
    @stack('styles') {{-- Page-specific styles --}}
</head>

<body>
    <header class="header-image">
        <img src="{{ asset('images/background_picture.jpeg') }}" alt="Villa Salud Header" class="header-banner">
    </header>


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

    @stack('scripts') {{-- Page-specific scripts --}}
</body>

</html>