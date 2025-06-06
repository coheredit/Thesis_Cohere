@extends('layouts.patron')

@section('title', 'View Reservation')

@push('styles')
    @vite('resources/css/p_vreserve.css')
@endpush

@section('content')
<div class="reservation-container">
    <h2>Reservation Form</h2>
    <p>To view your reservation form, please enter your Appointment Code and Email Address, then click "View Details."</p>
    <form method="POST" action="{{ route('patron.p_vreserve') }}">
        @csrf

        <div class="form-group">
            <label for="reservation_code">Reservation Code:</label>
            <input type="text" id="reservation_code" name="reservation_code" required />
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required />
        </div>

        <button type="submit" class="view-details">View Details</button>
    </form>
</div>

@endsection

@push('scripts')
    @vite('resources/js/p_vreserve.js')
@endpush
