@extends('layouts.admin')

@section('title', 'Reservation Logs')

@push('styles')
@vite('resources/css/reserve_logs.css')
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Reservation Logs</h1>
        <p>Manage and track all reservation requests</p>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Message</th>
                        <th>Venue</th>
                        <th>Event Type</th>
                        <th>Theme/Motif</th>
                        <th>Receipt</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->patron->name ?? 'N/A' }}</td>
                        <td>{{ $reservation->patron->email ?? '-' }}</td>
                        <td>{{ $reservation->date ?? '-' }}</td>
                        <td>{{ $reservation->time ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($reservation->message, 50) ?? '-' }}</td>
                        <td>{{ $reservation->venue ?? '-' }}</td>
                        <td>{{ $reservation->event_type ?? '-' }}</td>
                        <td>{{ $reservation->theme_motif ?? '-' }}</td>
                        <td>
                            <a href="#" class="receipt-link">View Receipt</a>
                        </td>
                        <td>
                            <select class="status-dropdown" data-id="{{ $reservation->id }}">
                                <option value="active" {{ $reservation->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="canceled" {{ $reservation->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" data-reservation-id="{{ $reservation->reserve_id }}" onclick="viewReservation(this.dataset.reservationId)">View</button>
                                <button class="btn-delete" data-reservation-id="{{ $reservation->reserve_id }}" onclick="deleteReservation(this.dataset.reservationId)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- View Modal -->
        <div id="viewModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Reservation Details</h2>
                <div id="modalBody">
                    Loading...
                </div>
            </div>
        </div>


        <div class="pagination">
            <button class="btn-pagination" id="prevBtn">Previous</button>
            <span class="page-info">Page 1 of 5</span>
            <button class="btn-pagination" id="nextBtn">Next</button>
        </div>
    </div>
</div>
@vite('resources/js/reserve_logs.js')
@endsection
