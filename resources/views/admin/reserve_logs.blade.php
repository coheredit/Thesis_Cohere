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
                    <!-- Sample data - replace with your dynamic data -->
                    <tr>
                        <td>John Doe</td>
                        <td>john.doe@email.com</td>
                        <td>2024-07-15</td>
                        <td>2:00 PM - 6:00 PM</td>
                        <td>Birthday celebration for my daughter...</td>
                        <td>Main Hall</td>
                        <td>Birthday Party</td>
                        <td>Princess Theme</td>
                        <td>
                            <a href="#" class="receipt-link">View Receipt</a>
                        </td>
                        <td>
                            <select class="status-dropdown" data-id="1">
                                <option value="active" selected>Active</option>
                                <option value="canceled">Canceled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewReservation(1)">View</button>
                                <button class="btn-delete" onclick="deleteReservation(1)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>jane.smith@email.com</td>
                        <td>2024-07-20</td>
                        <td>10:00 AM - 2:00 PM</td>
                        <td>Corporate meeting for quarterly review...</td>
                        <td>Conference Room</td>
                        <td>Corporate Event</td>
                        <td>Professional</td>
                        <td>
                            <a href="#" class="receipt-link">View Receipt</a>
                        </td>
                        <td>
                            <select class="status-dropdown" data-id="2">
                                <option value="active">Active</option>
                                <option value="canceled">Canceled</option>
                                <option value="completed" selected>Completed</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewReservation(2)">View</button>
                                <button class="btn-delete" onclick="deleteReservation(2)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Mike Johnson</td>
                        <td>mike.johnson@email.com</td>
                        <td>2024-07-25</td>
                        <td>6:00 PM - 11:00 PM</td>
                        <td>Wedding reception celebration...</td>
                        <td>Garden</td>
                        <td>Wedding</td>
                        <td>Rustic Garden</td>
                        <td>
                            <a href="#" class="receipt-link">View Receipt</a>
                        </td>
                        <td>
                            <select class="status-dropdown" data-id="3">
                                <option value="active">Active</option>
                                <option value="canceled" selected>Canceled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewReservation(3)">View</button>
                                <button class="btn-delete" onclick="deleteReservation(3)">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button class="btn-pagination" id="prevBtn">Previous</button>
            <span class="page-info">Page 1 of 5</span>
            <button class="btn-pagination" id="nextBtn">Next</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/reserve_logs.js')
@endpush