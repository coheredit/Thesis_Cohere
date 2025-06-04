@extends('layouts.patron')

@section('title', 'Patron Home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/p_home.css') }}">
@endpush

@section('content')
    <section class="home-hero">
        <h1>Welcome to Villa Salud Catering!</h1>
        <p>Your go-to destination for elegant events and hassle-free reservations.</p>
        <a href="{{ route('patron.reserve') }}" class="btn">Book Now</a>
    </section>

    <section class="features">
        <div class="feature">
            <h2>Make a Reservation</h2>
            <p>Book your event with ease in just a few clicks.</p>
        </div>
        <div class="feature">
            <h2>Track Your Booking</h2>
            <p>Stay updated with your reservation status anytime.</p>
        </div>
        <div class="feature">
            <h2>Secure Payments</h2>
            <p>Pay securely with multiple payment options available.</p>
        </div>
    </section>
@endsection

@push('scripts')
    @vite('resources/js/p_homepage.js')
@endpush
