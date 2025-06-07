@extends('layouts.patron')

@section('title', 'User Info Form')

@push('styles')
    @vite('resources/css/payment_order.css')
@endpush

@section('content')
<div class="payment-wrapper">
    <div class="payment-container">
        <div class="payment-header">
            <h2>User Information</h2>
            <p>Please enter your details below</p>
        </div>

        <h3 class="form-title">Fill out your details</h3>

        <form method="POST" action="#">
            @csrf

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
            </div>

            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Submit</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/payment_order.js')
@endpush
