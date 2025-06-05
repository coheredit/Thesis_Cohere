@extends('layouts.patron')

@section('title', 'Patron Home')

@push('styles')
    @vite('resources/css/p_home.css')
@endpush

@section('content')
    <div class="banner-container">
        <div class="banner">
            Welcome to Villa Salud Catering! Your go-to destination for elegant events and hassle-free reservations.
        </div>
    </div>

    <div class="packages">
        <div class="package-card">
            <img src="{{ asset('assets/theme1.jpg') }}" alt="Theme 1">
            <h3>Make a Reservation</h3>
            <a href="{{ route('patron.p_mreserve') }}" class="btn">Book Now</a>
        </div>

        <div class="package-card">
            <img src="{{ asset('assets/theme2.jpg') }}" alt="Theme 2">
            <h3>Track Your Booking</h3>
            <a href="{{ route('patron.p_vreserve') }}" class="btn">View Status</a>
        </div>

        <div class="package-card">
            <img src="{{ asset('assets/theme3.jpg') }}" alt="Theme 3">
            <h3>Secure Payments</h3>
            <a href="{{ route('patron.p_payment') }}" class="btn">Pay Now</a>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/p_homepage.js')
@endpush
