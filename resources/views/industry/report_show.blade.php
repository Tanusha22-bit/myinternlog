@extends('layouts.industry-dashboard')
@section('title', 'Report Detail')
@section('page_icon', 'bi-file-earmark-text')

@section('styles')
<style>
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.5rem 1.5rem;
        border: none;
        transition: background 0.2s;
    }
    .btn-indigo:hover { background: #4F46E5; }
    .card-modern {
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        background: #fff;
        padding: 2rem 2rem 1.5rem 2rem;
        border: none;
    }
    .label-purple {
        color: #6366F1;
        font-weight: 600;
    }
    .icon-purple {
        color: #6366F1;
    }
    .card-modern .text-muted {
        font-size: 1rem;
    }
    .card-modern h5 {
        margin-bottom: 0.5rem;
    }
    .card-modern p {
        margin-bottom: 0;
    }
    .status-label {
        color: #6366F1;
        font-weight: 600;
        font-size: 1.08rem;
    }
</style>
@endsection

@section('content')

<div class="card-modern mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h5>
                <i class="bi bi-person icon-purple"></i>
                <strong>{{ $report->student_name ?? 'Student' }}</strong>
                <span class="text-muted">({{ $report->student_matric ?? '' }})</span>
            </h5>
            <p>
                <span class="label-purple">Date:</span> {{ $report->report_date }}<br>
                <span class="label-purple">Task:</span> {{ $report->task }}<br>
                <span class="label-purple">Attachment:</span>
                @if($report->file)
                    <a href="{{ asset('storage/'.$report->file) }}" target="_blank" class="text-success">
                        <i class="bi bi-paperclip"></i> Download file
                    </a>
                @else
                    <span class="text-danger"><i class="bi bi-x-circle"></i> No file attached</span>
                @endif
            </p>
        </div>
        <div class="text-end">
            <span class="status-label">Status:</span>
            @if($report->industry_feedback)
                <span class="badge bg-success">Given</span>
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </div>
    </div>
</div>

<div class="card-modern">
    <h5 class="mb-3">Supervisor Feedback</h5>
    @if(!$report->industry_feedback)
        <form method="POST" action="{{ route('industry.reports.feedback', $report->id) }}">
            @csrf
            <textarea name="industry_feedback" class="form-control mb-3" rows="4" required></textarea>
            <button class="btn btn-indigo">Submit Feedback</button>
            <a href="{{ route('industry.reports') }}" class="btn btn-danger ms-2">Back to Reports</a>
        </form>
    @else
        <textarea class="form-control mb-3" rows="4" readonly>{{ $report->industry_feedback }}</textarea>
        <a href="{{ route('industry.reports') }}" class="btn btn-danger">Back to Reports</a>
    @endif
</div>
@endsection