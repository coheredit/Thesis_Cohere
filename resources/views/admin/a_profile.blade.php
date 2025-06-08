@extends('layouts.admin')

@section('title', 'Admin Profile')

@push('styles')
    @vite('resources/css/a_profile.css')
@endpush

@section('content')
@php
    $user = Auth::user() ?? (object)[
        'name' => 'Admin User',
        'email' => 'admin@villasalud.com',
        'phone' => 'N/A'
    ];
@endphp

<section class="dashboard-container">
    <div class="admin-profile">
        <div class="profile-header">
            <div class="profile-pic-container">
                <img src="{{ asset('assets/admin_picture.jpg') }}" alt="Admin Profile Picture" class="profile-pic" id="profile-pic">
                <button class="change-pic-btn" id="change-pic-btn">📷</button>
                <input type="file" id="profile-pic-input" accept="image/*">
            </div>
            <h2 id="admin-name">{{ $user->name }}</h2>
            <p>System Administrator</p>
        </div>
        <div class="profile-info">
            <h3>Profile Information</h3>
            <p><strong>Email:</strong> <span id="admin-email">{{ $user->email }}</span></p>
            <p><strong>Phone:</strong> <span id="admin-phone">{{ $user->phone }}</span></p>
            <p><strong>Username:</strong> <span id="admin-username">{{ explode('@', $user->email)[0] }}</span></p>
            <p><strong>Password:</strong> •••••••• <a href="#" class="change-password" id="change-password-link">Change</a></p>
        </div>
        <div class="buttons">
            <button class="edit-btn" id="edit-profile-btn">Edit Profile</button>
            <form method="POST" action="#">
                @csrf
                <button class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">Logout</button>
            </form>
        </div>
    </div>

     <div class="admin-history">
      <div class="history-header">
        <h3>Admin History</h3>
        <div class="history-controls">
          <select id="history-filter" class="filter-select">
            <option value="all">All Activities</option>
            <option value="login">Login/Logout</option>
            <option value="profile">Profile Changes</option>
            <option value="system">System Actions</option>
          </select>
          <button class="clear-history-btn" id="clear-history-btn">Clear History</button>
        </div>
      </div>
      
      <div class="history-stats">
        <div class="stat-item">
          <span class="stat-number" id="total-activities">0</span>
          <span class="stat-label">Total Activities</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" id="today-activities">0</span>
          <span class="stat-label">Today</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" id="this-week-activities">0</span>
          <span class="stat-label">This Week</span>
        </div>
      </div>

      <div class="history-list-container">
        <ul id="history-list" class="history-list">
          <!-- History items will be populated by JavaScript -->
        </ul>
        <div id="history-empty" class="history-empty" style="display: none;">
          <div class="empty-icon">📝</div>
          <p>No history available</p>
        </div>
      </div>
      
      <div class="history-pagination">
        <button id="load-more-btn" class="load-more-btn" style="display: none;">Load More</button>
      </div>
    </div>

    {{-- History, Stats, Modals (no changes needed) --}}
    {{-- ... KEEP ALL MODAL + HISTORY HTML UNCHANGED ... --}}
</section>
@endsection

@push('scripts')
    @vite('resources/js/a_profile.js')
@endpush


