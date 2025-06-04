@extends('layouts.admin')

@section('title', 'Make Reservation')

@push('scripts')
    @vite('resources/css/a_report.css')
@endpush

@section('content')
<div class="main-content">
    <h2 class="page-title">Inquiry Reports</h2>

    <div class="table-data">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Venue</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-12-01</td>
                    <td>Juan Dela Cruz</td>
                    <td>Villa I</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>2024-12-02</td>
                    <td>Maria Clara</td>
                    <td>Villa II</td>
                    <td>Confirmed</td>
                </tr>
                <tr>
                    <td>2024-12-03</td>
                    <td>Andres Bonifacio</td>
                    <td>Villa III</td>
                    <td>Cancelled</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/a_report.js')
@endpush

