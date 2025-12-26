@extends('layouts.university-dashboard')

@section('title','University Supervisor Dashboard')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Supervisor Dashboard</h2>
    <div class="avatar ms-3">{{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}</div>
</div>

<div class="row g-4">
    <!-- Student Overview -->
    <div class="col-md-4">
        <div class="card card-modern p-3">
            <div class="fw-bold mb-2"><i class="bi bi-people"></i> Student Overview</div>
            <div class="mb-2">Total Students: <span class="badge bg-info">12</span></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Tanusha <span class="badge bg-success">Active</span></li>
                <li class="list-group-item">Aiman <span class="badge bg-warning text-dark">Pending</span></li>
                <li class="list-group-item">Ravi <span class="badge bg-secondary">Completed</span></li>
            </ul>
            <a href="#" class="btn btn-outline-primary mt-3 w-100">View All Students</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card card-modern p-3">
            <div class="fw-bold mb-2"><i class="bi bi-lightning-charge"></i> Quick Actions</div>
            <div class="d-grid gap-2">
                <a href="#" class="btn btn-success">Add Feedback</a>
                <a href="#" class="btn btn-info">Schedule Meeting</a>
                <a href="#" class="btn btn-warning">Download Reports</a>
            </div>
        </div>
    </div>

    <!-- Reports Haven't Viewed -->
    <div class="col-md-4">
        <div class="card card-modern p-3">
            <div class="fw-bold mb-2"><i class="bi bi-eye-slash"></i> Reports Haven't Viewed</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Tanusha - Daily Report <span class="badge bg-danger">New</span></li>
                <li class="list-group-item">Aiman - Weekly Summary <span class="badge bg-danger">New</span></li>
            </ul>
            <a href="#" class="btn btn-outline-danger mt-3 w-100">View All Reports</a>
        </div>
    </div>
</div>
@endsection