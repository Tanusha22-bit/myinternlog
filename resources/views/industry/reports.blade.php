@extends('layouts.industry-dashboard')
@section('title', 'Report List')
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
    }
    .rounded-pill {
        border-radius: 999px !important;
    }
    .card-modern-purple {
        background: #6366F1;
        border-radius: 28px;
        color: #fff !important;
        padding: 2rem 1.5rem;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        border: none;
    }
    .table thead th {
    background: #0F172A;
    color: #fff;
    border: none;
}
    .card-modern-yellow {
        background: #fbbf24;
        border-radius: 28px;
        color: #fff !important;
        padding: 2rem 1.5rem;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 24px rgba(251,191,36,0.10);
        border: none;
    }
    .card-modern-green {
        background: #22C55E;
        border-radius: 28px;
        color: #fff !important;
        padding: 2rem 1.5rem;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 24px rgba(16,185,129,0.10);
        border: none;
    }

    /* Action buttons */
    .btn-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background: transparent;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
        margin-right: 8px;
        padding: 0;
    }
    .btn-icon.btn-view {
        color: #6366F1;
        border-color: #6366F1;
    }
    .btn-icon.btn-view:hover, .btn-icon.btn-view:focus {
        background: #6366F1;
        color: #fff;
    }
    .btn-icon.btn-edit {
        color: #fbbf24;
        border-color: #fbbf24;
    }
    .btn-icon.btn-edit:hover, .btn-icon.btn-edit:focus {
        background: #fbbf24;
        color: #fff;
    }
    .btn-icon.btn-edit:disabled, .btn-icon.btn-edit.disabled {
        color: #a1a1aa !important;
        border-color: #a1a1aa !important;
        background: #f3f4f6 !important;
        cursor: not-allowed !important;
        pointer-events: none;
    }
    
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('industry.reports', ['status' => 'all']) }}" style="text-decoration:none;">
            <div class="card-modern-purple text-center w-100 {{ $status == 'all' ? 'shadow' : '' }}">
                <div class="fw-bold mb-1" style="font-size:1.2rem;">
                    <i class="bi bi-clipboard-data" style="font-size:1.5rem;vertical-align:middle;"></i>
                    All Reports
                </div>
                <div class="display-6">{{ $counts['all'] ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('industry.reports', ['status' => 'pending']) }}" style="text-decoration:none;">
            <div class="card-modern-yellow text-center w-100 {{ $status == 'pending' ? 'shadow' : '' }}">
                <div class="fw-bold mb-1" style="font-size:1.2rem;">
                    <i class="bi bi-hourglass-split" style="font-size:1.5rem;vertical-align:middle;"></i>
                    Pending
                </div>
                <div class="display-6">{{ $counts['pending'] ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('industry.reports', ['status' => 'given']) }}" style="text-decoration:none;">
            <div class="card-modern-green text-center w-100 {{ $status == 'given' ? 'shadow' : '' }}">
                <div class="fw-bold mb-1" style="font-size:1.2rem;">
                    <i class="bi bi-check-circle" style="font-size:1.5rem;vertical-align:middle;"></i>
                    Given
                </div>
                <div class="display-6">{{ $counts['given'] ?? 0 }}</div>
            </div>
        </a>
    </div>
</div>

<div class="d-flex align-items-center justify-content-end gap-2 mb-3 flex-wrap">
    <form method="get" class="d-flex align-items-center gap-2 mb-0">
        <input type="date" name="date" class="form-control" style="max-width:200px;" value="{{ $date }}">
        <button class="btn btn-indigo" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="card-modern p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-list-task"></i> Report List</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>{{ $report->report_date }}</td>
                    <td>
                        <span class="badge"
                            style="
                                background: {{ $report->industry_feedback ? '#22C55E' : '#fbbf24' }};
                                color: {{ $report->industry_feedback ? '#fff' : '#92400E' }};
                                font-weight:600;
                                font-size:1em;
                            ">
                            {{ $report->industry_feedback ? 'Given' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('industry.reports.show', $report->id) }}" class="btn-icon btn-view" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        @if($report->industry_feedback)
                            <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $report->id }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                        @else
                        <button class="btn-icon btn-edit disabled" disabled title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted">No reports found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination links -->
        <div class="mt-3">
            {{ $reports->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>

    @foreach($reports as $report)
    <!-- Feedback Edit Modal -->
    <div class="modal fade" id="feedbackModal{{ $report->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $report->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content card-modern">
          <div class="modal-header">
            <h5 class="modal-title" id="feedbackModalLabel{{ $report->id }}">
                <i class="bi bi-pencil"></i> Edit Feedback
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form method="POST" action="{{ route('industry.reports.feedback', $report->id) }}">
            @csrf
            <div class="modal-body">
                <textarea name="industry_feedback" class="form-control" rows="5" required>{{ $report->industry_feedback }}</textarea>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-3">
                <button class="btn btn-indigo px-5" type="submit">Save</button>
                <button type="button" class="btn btn-danger px-5" onclick="showDeleteConfirm({{ $report->id }})">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endforeach
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header border-0">
        <h4 class="modal-title w-100 text-danger fw-bold" id="deleteConfirmModalLabel">Confirm Deletion</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">Are you sure you want to delete this feedback?</div>
        <form id="deleteFeedbackForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-4">Delete</button>
            <button type="button" class="btn btn-secondary ms-2 px-4" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function showDeleteConfirm(reportId) {
    // Set the form action to the correct route
    document.getElementById('deleteFeedbackForm').action = '/industry/reports/' + reportId + '/feedback';
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}
</script>
@endpush

@endsection