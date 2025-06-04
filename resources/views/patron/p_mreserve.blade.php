@extends('layouts.patron')

@section('title', 'Make Reservation')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/p_make_reservation.css') }}">
@endpush

@section('content')
<div class="reservation-container">
    <h2>Reserve Your Event</h2>
    <form method="POST" action="{{ route('patron.reserve.submit') }}">
        @csrf

        <div class="form-group">
            <label for="name">Full Name:<span>*</span></label>
            <input type="text" id="name" name="name" required />
        </div>

        <div class="form-group">
            <label for="email">Email:<span>*</span></label>
            <input type="email" id="email" name="email" required />
        </div>

        <div class="form-group">
            <label for="date">Event Date:<span>*</span></label>
            <input type="date" id="date" name="date" required />
        </div>

        <div class="form-group">
            <label for="theme">Theme/Motif:</label>
            <input type="text" id="theme" name="theme" />
        </div>

        <div class="form-group">
            <label for="message">Additional Notes:</label>
            <textarea id="message" name="message"></textarea>
        </div>

        <button type="submit">Submit Reservation</button>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/p_mreserve.js')
@endpush
