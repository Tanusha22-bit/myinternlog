@extends('layouts.university-dashboard')
@section('title', 'Report Detail')

@section('styles')
<style>
    .card-modern { border-radius: 18px; box-shadow: 0 2px 16px rgba(99,102,241,0.08); background: #fff; }
    .btn-indigo, .btn-danger-custom {
        border-radius: 999px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        border: none;
        transition: background 0.2s;
    }
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
    }
    .btn-indigo:hover { background: #4F46E5; }
    .btn-danger-custom {
        background: #EF4444;
        color: #fff !important;
    }
    .btn-danger-custom:hover { background: #DC2626; }
    .info-label { font-weight: bold; color: #6366F1; }
    .info-value { font-weight: 500; }
    .attachment-btn {
        background: #FBBF24;
        color: #0F172A !important;
        border-radius: 999px;
        font-weight: 500;
        padding: 0.3rem 1rem;
        border: none;
        transition: background 0.2s;
        font-size: 0.98rem;
    }
    .attachment-btn:hover { background: #F59E42; color: #fff !important; }
    .no-file-msg { color: #EF4444; font-weight: 500; font-size: 0.98rem; }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Report <span class="brand-highlight">Detail</span></h2>
</div>
<div class="card card-modern p-4 mb-4">
    <div class="mb-3 d-flex align-items-center" style="font-size:1.15rem;">
        <i class="bi bi-person me-2" style="font-size:1.3rem;color:#6366F1;"></i>
        <span class="fw-bold">{{ $report->student_name }}</span>
        <span class="ms-2 text-muted">({{ $report->matric_id }})</span>
    </div>
    <div class="row mb-2">
        <div class="col-md-6 mb-2">
            <span class="info-label">Date:</span>
            <span class="info-value ms-2">{{ $report->report_date }}</span>
        </div>
        <div class="col-md-6 mb-2">
            <span class="info-label">Status:</span>
            <span class="ms-2 badge bg-{{ $report->status == 'reviewed' ? 'success' : 'warning text-dark' }}">
                {{ ucfirst($report->status) }}
            </span>
        </div>
        <div class="col-md-12 mb-2">
            <span class="info-label">Log:</span>
            <span class="info-value ms-2">{{ $report->task }}</span>
        </div>
        <div class="col-md-12 mb-2">
    <span class="info-label">Industry Supervisor Feedback:</span>
    @if($report->industry_feedback)
        <span class="info-value ms-2">{{ $report->industry_feedback }}</span>
    @else
        <span class="no-file-msg ms-2"><i class="bi bi-x-circle"></i> No feedback given</span>
    @endif
</div>
        <div class="col-md-12 mb-2">
            <span class="info-label">Attachment:</span>
            @if($report->file)
                <a href="{{ asset('storage/' . $report->file) }}" target="_blank" class="attachment-btn ms-2">
                    <i class="bi bi-paperclip"></i> View File
                </a>
            @else
                <span class="no-file-msg ms-2"><i class="bi bi-x-circle"></i> No file attached</span>
            @endif
        </div>
    </div>
</div>
<div class="card card-modern p-4">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <form method="POST" action="{{ route('supervisor.university.report.feedback', $report->id) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Supervisor Feedback</label>
            <textarea name="uni_feedback" class="form-control" rows="4" 
                {{ $report->uni_feedback ? 'readonly' : 'required' }}>{{ $report->uni_feedback }}</textarea>
        </div>
        <div class="d-flex gap-2">
            @if(!$report->uni_feedback)
                <button class="btn btn-indigo">Submit Feedback</button>
            @endif
            <a href="{{ route('supervisor.university.student.reports', $report->student_id) }}" class="btn btn-danger-custom">Back to Reports</a>
        </div>
    </form>
</div>
@endsection