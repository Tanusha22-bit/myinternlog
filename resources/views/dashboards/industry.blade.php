@extends('layouts.industry-dashboard')
@section('title', 'Industry Supervisor Dashboard')

@section('content')
<h2 class="me-auto mb-0">
    Industry Supervisor <span class="brand-highlight" style="color:#6366F1;">Dashboard</span>
</h2>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-2"><i class="bi bi-people"></i> My Students</h5>
            <ul>
                <li>Student 1 - ABC University</li>
                <li>Student 2 - XYZ University</li>
                <li>Student 3 - DEF University</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-2"><i class="bi bi-list-task"></i> Task Status</h5>
            <ul>
                <li>Student 1: Task 1 - Completed</li>
                <li>Student 2: Task 2 - In Progress</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-2"><i class="bi bi-file-earmark-text"></i> Reports</h5>
            <ul>
                <li>Student 1: Report 1 - Submitted</li>
                <li>Student 2: Report 2 - Pending</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-2"><i class="bi bi-calendar-event"></i> Important Dates</h5>
            <ul>
                <li>2025-12-30: Final Report Due</li>
                <li>2026-01-10: Internship Ends</li>
            </ul>
        </div>
    </div>
</div>
@endsection