@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    {{-- Header Section with Action --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark">Welcome, Admin</h1>
            <p class="text-muted">Manage catering packages with ease</p>
        </div>
        <a href="#" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Add New Package
        </a>
    </div>

    {{-- Promotional Banner --}}
    <div class="alert text-white text-center rounded-3 shadow-sm mb-5" style="background-color: #007b3d;">
        ✨ Big events, bigger savings! Grab our special deals for weddings, birthdays, and more! ✨
    </div>

    {{-- Package Cards Grid --}}
    <div class="row g-4">
        @foreach([
            ['name' => 'Baptism Package', 'price' => '₱15,000.00', 'img' => 'baptism_package.jpg'],
            ['name' => 'Debut Package', 'price' => '₱25,000.00', 'img' => 'debut_package.jpg'],
            ['name' => 'Wedding Package', 'price' => '₱50,000.00', 'img' => 'wedding_package.jpg'],
            ['name' => 'Kiddie Package', 'price' => '₱12,000.00', 'img' => 'kiddie_package.jpg'],
        ] as $pkg)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ asset('images/' . $pkg['img']) }}" class="card-img-top rounded-top" alt="{{ $pkg['name'] }}" style="height: 180px; object-fit: cover;">

                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="mb-3">
                        <h5 class="card-title fw-semibold">{{ $pkg['name'] }}</h5>
                        <p class="text-success fw-bold mb-0">{{ $pkg['price'] }}</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-success btn-sm w-100">View</button>
                        <button class="btn btn-warning btn-sm text-white w-100">Edit</button>
                        <button class="btn btn-danger btn-sm w-100">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/a_homepage.js')
@endpush

@push('styles')
    @vite('resources/css/a_home.css')
@endpush
