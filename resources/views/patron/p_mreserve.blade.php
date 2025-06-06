@extends('layouts.patron')

@section('title', 'Make Reservation')

@push('styles')
    @vite('resources/css/p_mreserve.css')
@endpush

@section('content')
<div class="container">
    <div class="reservation-container">
        <h2>Reserve Your Event</h2>
        <form method="POST" action="{{ route('patron.p_mreserve') }}">
            @csrf

            <div class="form-group">
                <label for="name">Name:<span>*</span></label>
                <input type="text" id="name" name="name" placeholder="First Name, Last Name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:<span>*</span></label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number:<span>*</span></label>
                <input type="tel" id="contact" name="contact_number" required>
            </div>

            <div class="form-group">
                <label for="date">Date:<span>*</span></label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Time:<span>*</span></label>
                <input type="time" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="venue">Venue:<span>*</span></label>
                <select id="venue" name="venue">
                    <option value="Villa I">Villa I</option>
                    <option value="Villa II">Villa II</option>
                    <option value="Elizabeth Hall">Elizabeth Hall</option>
                    <option value="Private Pool">Private Pool</option>
                    <option value="Others">Others</option>
                </select>
                <input type="text" id="otherVenue" name="other_venue" style="display: none;" placeholder="Please specify">
            </div>

            <div class="form-group">
                <label for="event_type">Event Type:<span>*</span></label>
                <select id="event_type" name="event_type">
                    <option value="Baptismal Package">Baptismal Package</option>
                    <option value="Birthday Package">Birthday Package</option>
                    <option value="Debut Package">Debut Package</option>
                    <option value="Kiddie Package">Kiddie Package</option>
                    <option value="Wedding Package">Wedding Package</option>
                    <option value="Standard Package">Standard Package</option>
                    <option value="Others">Others</option>
                </select>
                <input type="text" id="otherEventType" name="other_event_type" style="display: none;" placeholder="Please specify">
            </div>

            <div class="form-group">
                <label for="theme_motif">Theme/Motif:<span>*</span></label>
                <select id="theme_motif" name="theme_motif">
                    <option value="Floral">Floral</option>
                    <option value="Rustic">Rustic</option>
                    <option value="Elegant">Elegant</option>
                    <option value="Beach">Beach</option>
                    <option value="Modern">Modern</option>
                    <option value="Others">Others</option>
                </select>
                <input type="text" id="otherThemeMotif" name="other_theme_motif" style="display: none;" placeholder="Please specify">
            </div>

            <div class="form-group">
                <label for="message">Other Request:<span>*</span></label>
                <textarea id="message" name="message" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit Inquiry</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
    @vite('resources/js/p_mreserve.js')
@endpush
 
