@extends('layouts.student-dashboard')

@section('styles')
<style>
.filter-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: #222;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center; /* Center icon and text horizontally */
    gap: 0.5rem;
    text-decoration: none !important; /* Remove underline */
    text-align: center; /* Center text inside */
}
.filter-card.all:hover,
.filter-card.all.active {
    background: #6366F1;
    color: #fff;
    border-color: #6366F1;
}
.filter-card.submitted:hover,
.filter-card.submitted.active {
    background: #FBBF24;
    color: #222;
    border-color: #FBBF24;
}
.filter-card.reviewed:hover,
.filter-card.reviewed.active {
    background: #22C55E;
    color: #fff;
    border-color: #22C55E;
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

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Report<span class="brand-highlight">List</span></h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-md-3">
        <a href="{{ route('daily-report.list') }}"
            class="filter-card all{{ empty($activeStatus) ? ' active' : '' }}">
            <i class="bi bi-collection"></i> All
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'submitted']) }}"
           class="filter-card submitted{{ $activeStatus === 'submitted' ? ' active' : '' }}">
            <i class="bi bi-send-check"></i> Submitted
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'reviewed']) }}"
           class="filter-card reviewed{{ $activeStatus === 'reviewed' ? ' active' : '' }}">
            <i class="bi bi-check-circle"></i> Reviewed
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