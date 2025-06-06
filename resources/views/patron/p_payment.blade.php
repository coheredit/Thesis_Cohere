@extends('layouts.patron')

@section('title', 'Payment Order')

@push('styles')
    @vite('resources/css/payment_order.css')
@endpush

@section('content')
<div class="payment-wrapper">
    <div class="payment-container">
        <div class="payment-header">
            <h2>Payment Order</h2>
            <p>Complete your order by selecting a payment method</p>
        </div>

        <h3 class="form-title">Fill out your order details</h3>

        <form method="POST" action="{{ route('patron.p_payment') }}">
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
                <label for="amount">Payment Amount (PHP):</label>
                <input type="number" id="amount" name="amount" placeholder="Minimum amount: PHP 5,000" required>
            </div>

            <div class="form-group">
                <label for="downpayment">Downpayment Option:</label>
                <select id="downpayment" name="downpayment">
                    <option>Full Payment (100%)</option>
                    <option>50% Downpayment</option>
                </select>
            </div>

            <div class="form-group">
                <label for="method">Payment Method:</label>
                <select id="method" name="method">
                    <option>GCash</option>
                    <option>Bank Transfer</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Generate Payment Order</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    @vite('resources/js/payment_order.js')
@endpush
