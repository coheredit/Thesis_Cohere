@extends('layouts.patron')

@section('title', 'View Reservation')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/p_view_reservation.css') }}">
@endpush

@section('content')
<div class="reservation-container">
    <h2>View Your Reservation</h2>
    <form method="POST" action="{{ route('patron.view.submit') }}">
        @csrf

        <div class="form-group">
            <label for="reservation_code">Reservation Code:</label>
            <input type="text" id="reservation_code" name="reservation_code" required />
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required />
        </div>

        <button type="submit">View Details</button>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/p_vreserve.js')
@endpush
