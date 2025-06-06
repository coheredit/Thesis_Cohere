@extends('layouts.admin')

@section('title', 'View Inquiries')

@push('styles')
    @vite('resources/css/a_inquiry.css')
@endpush

@section('content')
<div class="inquiry-content">

    <section id="content">
        <nav>
            <h2>View Inquiries</h2>
        </nav>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Customer Inquiries</h1>
                    <p>View and manage customer inquiries.</p>
                </div>
            </div>

            @php
                $inquiries = collect([
                    (object)[
                        'inquiry_id' => 1,
                        'full_name' => 'Sample Name',
                        'email' => 'sample@email.com',
                        'contact_number' => '1234567890',
                        'time' => '14:00:00',
                        'date' => '2025-07-01',
                        'venue' => 'Sample Venue',
                        'event_type' => 'Birthday Package',
                        'theme_motif' => 'Rustic',
                        'other_event_type' => '',
                        'other_theme_motif' => '',
                        'other_venue' => '',
                        'status' => 'Pending',
                        'created_by_type' => 'patron'
                    ]
                ]);
            @endphp

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Inquiries</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Time</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Event Type</th>
                                <th>Theme and Motif</th>
                                <th>Other Event Type</th>
                                <th>Other Theme Motif</th>
                                <th>Other Venue</th>
                                <th>Status</th>
                                <th>Submitted By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquiries as $index => $inquiry)
                                <tr data-inquiry-id="{{ $inquiry->inquiry_id }}">
                                    <td>{{ $inquiry->full_name ?? 'Admin' }}</td>
                                    <td>{{ $inquiry->email ?? '-' }}</td>
                                    <td>{{ $inquiry->contact_number ?? '-' }}</td>
                                    <td>{{ $inquiry->time }}</td>
                                    <td>{{ $inquiry->date }}</td>
                                    <td>{{ $inquiry->venue }}</td>
                                    <td>{{ $inquiry->event_type }}</td>
                                    <td>{{ $inquiry->theme_motif }}</td>
                                    <td>{{ $inquiry->other_event_type }}</td>
                                    <td>{{ $inquiry->other_theme_motif }}</td>
                                    <td>{{ $inquiry->other_venue }}</td>
                                    <td>
                                        <select class="status-dropdown" data-index="{{ $index }}">
                                            <option value="" {{ $inquiry->status == null ? 'selected hidden' : 'hidden' }}>Set Status</option>
                                            <option value="Pending" {{ $inquiry->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $inquiry->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $inquiry->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $inquiry->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>{{ $inquiry->created_by_type ?? 'unknown' }}</td>
                                    <td>
                                        <button class="reply-btn" data-index="{{ $index }}">Reply</button>
                                        @if ($inquiry->status === 'Completed')
                                            <button class="undo-btn" data-inquiry-id="{{ $inquiry->inquiry_id }}">Undo</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </section>

    <!-- Modal for setting status (optional for now) -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h2>Set Inquiry Status</h2>
            <select id="statusSelect">
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <button id="saveStatusBtn">Save</button>
            <button id="closeModalBtn">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/a_inquiries.js')
@endpush
