@extends('layouts.patron')

@section('title', 'Submit Payment')

@push('styles')
    @vite('resources/css/payment_order.css')
@endpush

@section('content')
<div class="payment-wrapper">
    <div class="payment-container">
        <div class="payment-header">
            <h2>Submit Proof of Payment</h2>
            <p>Please provide your payment details and receipt.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @elseif(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('patron.payment.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="tracking_code">Tracking Code:</label>
                <input type="text" name="tracking_code" id="tracking_code" required>
            </div>

            <div class="form-group">
                <label for="reference_code">Reference Code (optional):</label>
                <input type="text" name="reference_code" id="reference_code">
            </div>

            <div class="form-group">
                <label for="receipt">Upload Receipt (jpg, png, or pdf):</label>
                <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            <button type="submit" class="btn-submit">Submit Payment</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/payment_order.js')
@endpush
