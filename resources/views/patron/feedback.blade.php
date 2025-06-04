@extends('layouts.patron')

@section('title', 'Feedback')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/feedback.css') }}">
@endpush

@section('content')
<div class="feedback-container">
    <h2>We’d love your feedback!</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('patron.feedback.submit') }}">
        @csrf

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message">{{ old('message') }}</textarea>
            @error('message')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">Send Feedback</button>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/feedback.js')
@endpush
