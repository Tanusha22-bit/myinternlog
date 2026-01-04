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
.btn-warning {
    background: #FBBF24;
    color: #222;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-warning:hover, .btn-warning:focus {
    background: #eab308;
    color: #222;
}
.btn-danger {
    background: #EF4444;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-danger:hover, .btn-danger:focus {
    background: #dc2626;
    color: #fff;
}
.action-btns {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}
</style>
@endsection

@section('title', 'Report Detail')
@section('page-title')
    <h2 class="mb-0">
        <i class="bi-file-earmark-text me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Report<span style="color:#6366F1;">Detail</span></span>
    </h2>
@endsection

@section('content')
<div class="card card-modern p-4 mb-3">
    <div class="mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</div>
    <div class="mb-3">
        <strong>Log:</strong>
        <div class="border rounded p-2 bg-white">{{ $report->task }}</div>
    </div>
    <div class="mb-3">
        <strong>Status:</strong>
        <span class="badge {{ $report->status == 'reviewed' ? 'bg-success' : 'bg-warning text-dark' }}">
            {{ ucfirst($report->status) }}
        </span>
    </div>
    @if($report->file)
    <div class="mb-3">
        <strong>Attachment:</strong>
        <a href="{{ asset('storage/' . $report->file) }}" target="_blank" class="btn btn-indigo btn-sm ms-2">
            <i class="bi bi-paperclip"></i> View File
        </a>
    </div>
    @endif
    <div class="mb-3">
        <strong>University Supervisor Feedback:</strong>
        <div class="border rounded p-2 bg-white">
            @if($report->uni_feedback)
                {{ $report->uni_feedback }}
            @else
                <span class="text-muted">No feedback yet.</span>
            @endif
        </div>
    </div>
    <div class="mb-3">
        <strong>Industry Supervisor Feedback:</strong>
        <div class="border rounded p-2 bg-white">
            @if($report->industry_feedback)
                {{ $report->industry_feedback }}
            @else
                <span class="text-muted">No feedback yet.</span>
            @endif
        </div>
    </div>
    <div class="action-btns">
        <a href="{{ route('daily-report.list') }}" class="btn btn-indigo">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
        @if($report->status === 'submitted')
        <a href="{{ route('daily-report.edit', $report->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <form action="{{ route('daily-report.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
        @endif
    </div>
</div>
@endsection