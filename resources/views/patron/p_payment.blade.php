@extends('layouts.patron')

@section('title', 'Payment Order')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/payment_order.css') }}">
@endpush

@section('content')
<div class="payment-container">
    <h2>Payment Order</h2>

    <form id="paymentForm" novalidate>
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" required />
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" required />
        </div>

        <div class="form-group">
            <label for="orderAmount">Payment Amount (PHP):</label>
            <input type="number" id="orderAmount" min="5000" required />
        </div>

        <div class="form-group">
            <label for="downpaymentType">Downpayment Option:</label>
            <select id="downpaymentType">
                <option value="full">Full Payment (100%)</option>
                <option value="half">Half Payment (50%)</option>
                <option value="thirty">Partial Payment (30%)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod">
                <option value="gcash">GCash</option>
                <option value="bank">Bank Transfer</option>
            </select>
        </div>

        <button type="button" id="generateOrder">Generate Payment Order</button>
    </form>

    <div class="payment-instructions" id="paymentInstructions" style="display: none;">
        <h3>Instructions will appear here after submitting the form.</h3>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/payment_order.js')
@endpush
