@extends('layouts.student-dashboard')
@section('styles')
<style>
.btn-indigo {
    background: #6366F1;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4F46E5;
    color: #fff;
}
.btn-rounded {
    border-radius: 999px !important;
    padding: 0.7rem 2.5rem !important;
    font-size: 1.2rem !important;
    font-weight: 500;
}
</style>
@endsection

@section('title', 'Logbook Preview')

@section('content')
<div class="card card-modern p-4 mb-4">
    <h3 class="mb-3">Logbook Preview</h3>
    <div style="max-height: 70vh; overflow-y: auto;">
        @include('daily_reports.pdf', [
            'student' => $student,
            'internship' => $internship,
            'reports' => $reports
        ])
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('daily-report.pdf') }}" class="btn btn-warning btn-rounded me-2" style="font-size:1.2rem; padding:0.7rem 2.5rem;">
            <i class="bi bi-download"></i> Download PDF
        </a>
                <a href="{{ route('daily-report.create') }}" class="btn btn-indigo btn-rounded me-2" style="font-size:1.1rem; padding:0.5rem 2rem;">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>
@endsection