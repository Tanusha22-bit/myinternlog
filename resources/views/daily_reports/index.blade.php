@extends('layouts.student-dashboard')

@section('styles')
<style>
.dashboard-card {
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    color: #22223b;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none !important;
    transition: box-shadow 0.2s, filter 0.2s;
}
.bg-indigo { background: #6366F1; color: #fff !important; }
.bg-yellow { background: #FACC15; color: #92400E !important; }
.bg-green { background: #22C55E; color: #fff !important; }
.dashboard-card .fw-bold {
    font-size: 1.1rem;
}
.dashboard-card .display-6 {
    font-size: 2.2rem;
    font-weight: 700;
}
.dashboard-card .stat-icon {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
}
.dashboard-card .stat-label {
    font-size: 1.1rem;
    font-weight: 600;
}
.dashboard-card .stat-count {
    font-size: 2.2rem;
    font-weight: bold;
    margin-top: 0.5rem;
}
.dashboard-card:hover, .dashboard-card.active {
    filter: brightness(0.97);
    box-shadow: 0 4px 24px rgba(99,102,241,0.12);
    text-decoration: none !important;
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
.action-btn {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 4px;
    border: 2px solid transparent;
    background: #fff;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.action-btn.view {
    border-color: #6366F1;
    color: #6366F1;
}
.action-btn.view:hover, .action-btn.view:focus {
    background: #6366F1;
    color: #fff;
}
.action-btn.edit {
    border-color: #fbbf24;
    color: #fbbf24;
}
.action-btn.edit:hover, .action-btn.edit:focus {
    background: #fbbf24;
    color: #fff;
}
.action-btn.delete {
    border-color: #ef4444;
    color: #ef4444;
}
.action-btn.delete:hover, .action-btn.delete:focus {
    background: #ef4444;
    color: #fff;
}
.action-btn:active {
    opacity: 0.8;
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

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-4 justify-content-center">
    <div class="col-md-3">
        <a href="{{ route('daily-report.list') }}" class="dashboard-card bg-indigo{{ empty($activeStatus) ? ' active' : '' }}" style="text-decoration:none;">
            <div class="stat-icon"><i class="bi bi-collection"></i></div>
            <div class="stat-label">All</div>
            <div class="stat-count">{{ $allCount }}</div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'submitted']) }}" class="dashboard-card bg-yellow{{ $activeStatus === 'submitted' ? ' active' : '' }}" style="text-decoration:none;">
            <div class="stat-icon"><i class="bi bi-send-check"></i></div>
            <div class="stat-label">Submitted</div>
            <div class="stat-count">{{ $submittedCount }}</div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('daily-report.list', ['status' => 'reviewed']) }}" class="dashboard-card bg-green{{ $activeStatus === 'reviewed' ? ' active' : '' }}" style="text-decoration:none;">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-label">Reviewed</div>
            <div class="stat-count">{{ $reviewedCount }}</div>
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
                    <td>
                        <a href="{{ route('daily-report.show', $report->id) }}" class="action-btn view" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($report->status === 'submitted')
                        <a href="{{ route('daily-report.edit', $report->id) }}" class="action-btn edit" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="action-btn delete" title="Delete"
                            onclick="showDeleteConfirmModal('{{ route('daily-report.destroy', $report->id) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- Pagination --}}
        <div class="mt-3">
            {{ $reports->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteConfirmForm" class="modal-content" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold w-100 text-center text-danger" id="deleteConfirmModalLabel">Confirm Deletion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this report?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="submit" class="btn" style="background:#ef4444;color:#fff;border-radius:8px;font-weight:600;width:100px;">Delete</button>
                <button type="button" class="btn btn-secondary" style="border-radius:8px;width:100px;" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showDeleteConfirmModal(action) {
    document.getElementById('deleteConfirmForm').action = action;
    var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}
</script>
@endpush

@endsection