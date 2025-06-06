@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

{{-- Promotional Banner --}}
<section class="banner-container">
    <div class="banner text-center text-white fw-bold py-2">
        ✨ Big events, bigger savings! Grab our special deals for weddings, birthdays, and more! ✨
    </div>
</section>

{{-- Package Cards --}}
<section class="packages row" id="packagesContainer">
    @foreach([
        ['name' => 'Baptism Package', 'img' => 'baptism_package.jpg'],
        ['name' => 'Debut Package', 'img' => 'debut_package.jpg'],
        ['name' => 'Wedding Package', 'img' => 'wedding_package.jpg'],
        ['name' => 'Kiddie Package', 'img' => 'kiddie_package.jpg'],
    ] as $pkg)
    <div class="package-wrapper">
    <div class="package-card" data-package="{{ $pkg['name'] }}">
        <img src="{{ asset('images/' . $pkg['img']) }}" alt="{{ $pkg['name'] }}">
        <h3>{{ $pkg['name'] }}</h3>
        <div class="package-details"></div>
        <div class="buttons d-flex flex-column gap-2">
            <button class="btn btn-outline-success btn-sm view-package view-btn">View Package</button>
            <button class="btn btn-warning btn-sm text-white edit-btn">Edit</button>
            <button class="btn btn-danger btn-sm delete-btn">Delete</button>
        </div>
    </div>
</div>
    </div>
    @endforeach
</section>

{{-- Add New Package Button --}}
<div class="add-package-container text-end mt-4">
    <button id="addPackageBtn" class="add-package-btn btn btn-success shadow-sm">+ Add New Package</button>
</div>
@endsection

@push('scripts')
    @vite('resources/js/a_homepage.js')
@endpush

@push('styles')
    @vite('resources/css/a_home.css')
@endpush
