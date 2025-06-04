@extends('layouts.admin')

@section('title', 'View Inquiries')

@push('scripts')
    @vite('resources/css/a_inquiry.css')
@endpush


@section('content')
<div class="inquiry-content">
    <h1>View Inquiries</h1>

    <form method="GET" action="{{ route('admin.inquiry') }}" class="filter-form">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status">
            <option value="">All</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="declined">Declined</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <table class="inquiry-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Email</th>
                <th>Event Type</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>RES-001</td>
                <td>Juan Dela Cruz</td>
                <td>juan@email.com</td>
                <td>Wedding</td>
                <td>2025-07-15</td>
                <td><span class="badge pending">Pending</span></td>
                <td><a href="#">View</a> | <a href="#">Approve</a></td>
            </tr>
            <tr>
                <td>RES-002</td>
                <td>Maria Santos</td>
                <td>maria@email.com</td>
                <td>Debut</td>
                <td>2025-08-02</td>
                <td><span class="badge approved">Approved</span></td>
                <td><a href="#">View</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
    @vite('resources/js/a_inquiries.js')
@endpush

