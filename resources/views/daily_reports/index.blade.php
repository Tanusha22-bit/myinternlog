@extends('layouts.student-dashboard')

@section('styles')
<style>
.filter-card {
    background: #fff;
    border-radius: 28px; /* more rounded like your sample */
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    padding: 2.2rem 0.5rem 1.2rem 0.5rem; /* more vertical space */
    font-weight: 600;
    font-size: 1.1rem;
    color: #222;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    text-align: center;
    width: 200px;
    height: 140px;
    min-width: 180px;
    min-height: 120px;
    margin: 0 12px;
}
.filter-card .filter-count {
    font-size: 2.2rem;
    font-weight: bold;
    color: #222; /* same as text by default */
    background: none;
    border-radius: 0;
    padding: 0;
    margin-top: 0.8rem;
    line-height: 1;
    transition: color 0.2s;
}
.filter-card.all:hover,
.filter-card.all.active {
    background: #6366F1;
    color: #fff;
    border-color: #6366F1;
}
.filter-card.all:hover .filter-count,
.filter-card.all.active .filter-count {
    color: #fff;
}
.filter-card.submitted:hover,
.filter-card.submitted.active {
    background: #FBBF24;
    color: #fff;
    border-color: #FBBF24;
}
.filter-card.submitted:hover .filter-count,
.filter-card.submitted.active .filter-count {
    color: #fff;
}
.filter-card.reviewed:hover,
.filter-card.reviewed.active {
    background: #22C55E;
    color: #fff;
    border-color: #22C55E;
}
.filter-card.reviewed:hover .filter-count,
.filter-card.reviewed.active .filter-count {
    color: #fff;
}
.table-modern th {
    background: #0F172A;
    color: #fff;
    border: none;
}
.table-modern td {
    border: none;
    font-size: 1rem;
}
.table-modern tr {
    border-bottom: 1px solid #f3f4f6;
}
.table-modern tr:last-child {
    border-bottom: none;
}
.btn-view {
    background: #6366F1;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.btn-view:hover {
    background: #4F46E5;
    color: #fff;
}
</style>
@endsection

@section('title', 'Report List')
@section('page-title')
    <h2 class="mb-0">
        <i class="bi-list-check me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Report<span style="color:#6366F1;">List</span></span>
    </h2>
@endsection

@section('content')

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-md-3">
        <a href="{{ route('daily-report.list') }}"
            class="filter-card all{{ empty($activeStatus) ? ' active' : '' }}">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <i class="bi bi-collection mb-1"></i>
                <span>All</span>
                <span class="filter-count">{{ $allCount }}</span>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'submitted']) }}"
           class="filter-card submitted{{ $activeStatus === 'submitted' ? ' active' : '' }}">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <i class="bi bi-send-check mb-1"></i>
                <span>Submitted</span>
                <span class="filter-count">{{ $submittedCount }}</span>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'reviewed']) }}"
           class="filter-card reviewed{{ $activeStatus === 'reviewed' ? ' active' : '' }}">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <i class="bi bi-check-circle mb-1"></i>
                <span>Reviewed</span>
                <span class="filter-count">{{ $reviewedCount }}</span>
            </div>
        </a>
    </div>
</div>

<div class="card card-modern p-4">
    <h5 class="mb-3"><i class="bi bi-journal-text"></i> Your Submitted Reports</h5>
    @if($reports->isEmpty())
        <div class="alert alert-info">No reports submitted yet.</div>
    @else
    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Log Preview</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                    <td>{{ Str::limit($report->task, 40) }}</td>
                    <td>
                        <span class="badge {{ $report->status == 'reviewed' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td>
                        @if($report->uni_feedback || $report->industry_feedback)
                            <i class="bi bi-chat-dots text-success" title="Feedback available"></i>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('daily-report.show', $report->id) }}" class="btn-view" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('daily-report.edit', $report->id) }}" class="btn-view" style="background:#FBBF24; color:#222;" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                    <form action="{{ route('daily-report.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                    <button type="submit" class="btn-view" style="background:#EF4444;" title="Delete">
                        <i class="bi bi-trash" style="color:#fff;"></i>
                    </button>
                    </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection