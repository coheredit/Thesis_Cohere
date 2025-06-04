@extends('layouts.patron')

@section('title', 'FAQs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('style/faqs.css') }}">
@endpush

@section('content')
<div class="faq-container">
    <h1>Frequently Asked Questions</h1>

    <div class="faq">
        <button class="faq-question">Do you accept outside catering? <span class="arrow">&#9660;</span></button>
        <div class="faq-answer">Yes! We accept outside catering subject to availability and approval.</div>
    </div>

    <div class="faq">
        <button class="faq-question">Is there parking at the venue? <span class="arrow">&#9660;</span></button>
        <div class="faq-answer">Yes! We offer parking spaces for up to 20 vehicles.</div>
    </div>

    <!-- Add more FAQ items as needed -->
</div>
@endsection

@push('scripts')
    @vite('resources/js/faqs.js')
@endpush
