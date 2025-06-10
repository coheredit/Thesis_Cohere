@extends('layouts.admin')

@section('title', 'Admin Profile')

@push('styles')
@vite('resources/css/a_profile.css')
@endpush

@section('content')
@php
$user = Auth::guard('admin')->user() ?? (object)[
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
    <h3>Admin History</h3>

    <div class="d-flex justify-content-between mb-2">
      <select id="activityFilter" class="form-select w-auto">
        <option value="all">All Activities</option>
        <option value="login">Login/Logout</option>
        <option value="profile">Profile Changes</option>
        <option value="system">System Actions</option>
      </select>
      <button class="btn btn-danger btn-sm" id="clearHistoryBtn">Clear History</button>
    </div>

    <div id="activityLogStats" class="mb-3 d-flex gap-4">
      <div><strong id="totalActivities">0</strong><br><small>Total Activities</small></div>
      <div><strong id="todayActivities">0</strong><br><small>Today</small></div>
      <div><strong id="weekActivities">0</strong><br><small>This Week</small></div>
    </div>

    <div id="activityLogContainer">
      <p class="text-muted">Loading activity logs...</p>
    </div>
  </div>


  {{-- History, Stats, Modals (no changes needed) --}}
  {{-- ... KEEP ALL MODAL + HISTORY HTML UNCHANGED ... --}}
</section>
@endsection

@push('scripts')
@vite('resources/js/a_profile.js')
@endpush