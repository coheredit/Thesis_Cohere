@extends('layouts.patron')

@section('title', 'Manual Reservation')

@push('styles')
    @vite('resources/css/p_mreserve.css')
@endpush

@section('content')
<div class="container">
    <div class="reservation-container">
        <h2>Manual Reservation Form</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('patron.p_mreserve.submit') }}">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            {{-- Contact --}}
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" required>
            </div>

            {{-- Event Type (ENUM) --}}
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select name="event_type" id="event_type" required>
                    <option disabled selected value="">Select Event Type</option>
                    <option value="Baptismal Package">Baptismal Package</option>
                    <option value="Birthday Package">Birthday Package</option>
                    <option value="Debut Package">Debut Package</option>
                    <option value="Wedding Package">Wedding Package</option>
                </select>
            </div>

            {{-- Other Event Type --}}
            <div class="form-group">
                <label for="other_event_type">Other Event Type (optional)</label>
                <input type="text" name="other_event_type" id="other_event_type" value="{{ old('other_event_type') }}">
            </div>

            {{-- Theme (ENUM) --}}
            <div class="form-group">
                <label for="theme_motif">Theme / Motif</label>
                <select name="theme_motif" id="theme_motif" required>
                    <option disabled selected value="">Select Theme</option>
                    <option value="Floral">Floral</option>
                    <option value="Rustic">Rustic</option>
                    <option value="Elegant">Elegant</option>
                    <option value="Beach">Beach</option>
                    <option value="Modern">Modern</option>
                </select>
            </div>

            {{-- Other Theme --}}
            <div class="form-group">
                <label for="other_theme_motif">Other Theme (optional)</label>
                <input type="text" name="other_theme_motif" id="other_theme_motif" value="{{ old('other_theme_motif') }}">
            </div>

            {{-- Venue (ENUM) --}}
            <div class="form-group">
                <label for="venue">Venue</label>
                <select name="venue" id="venue" required>
                    <option disabled selected value="">Select Venue</option>
                    <option value="Villa I">Villa I</option>
                    <option value="Villa II">Villa II</option>
                    <option value="Elizabeth Hall">Elizabeth Hall</option>
                    <option value="Private Venue">Private Venue</option>
                </select>
            </div>

            {{-- Other Venue --}}
            <div class="form-group">
                <label for="other_venue">Other Venue (optional)</label>
                <input type="text" name="other_venue" id="other_venue" value="{{ old('other_venue') }}">
            </div>

            {{-- Date --}}
            <div class="form-group">
                <label for="date">Event Date</label>
                <input type="date" name="date" id="date" value="{{ old('date') }}" required>
            </div>

            {{-- Time --}}
            <div class="form-group">
                <label for="time">Event Time</label>
                <input type="time" name="time" id="time" value="{{ old('time') }}" required>
            </div>

            {{-- Message --}}
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="4" required>{{ old('message') }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit Reservation</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/p_mreserve.js')
@endpush
