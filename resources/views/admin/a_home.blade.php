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
                <button class="btn btn-outline-success btn-sm view-package view-btn" data-bs-toggle="modal" data-bs-target="#packageModal">View Package</button>
                <button class="btn btn-warning btn-sm text-white edit-btn" data-bs-toggle="modal" data-bs-target="#editPackageModal">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn">Delete</button>
            </div>
        </div>
    </div>
    @endforeach
</section>

{{-- Add New Package Button --}}
<div class="add-package-container text-end mt-4">
    <button id="addPackageBtn" class="add-package-btn btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">+ Add New Package</button>
</div>

{{-- View Package Modal --}}
<div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="packageModalLabel">Package Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="packageModalDescription">Description goes here...</p>
            </div>
        </div>
    </div>
</div>

{{-- Edit Package Modal --}}
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="editPackageModalName" class="form-control mb-2" placeholder="Package Name">
                <textarea id="editPackageModalDescription" class="form-control" rows="4" placeholder="Description"></textarea>
            </div>
        </div>
    </div>
</div>

{{-- Add Package Modal --}}
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPackageModalLabel">Add New Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="newPackageName" class="form-control mb-2" placeholder="Package Name">
                <textarea id="newPackageDescription" class="form-control" rows="4" placeholder="Description"></textarea>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite('resources/js/a_homepage.js')
@endpush

@push('styles')
    @vite('resources/css/a_home.css')
@endpush
