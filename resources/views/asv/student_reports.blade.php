@extends('layouts.university-dashboard')
@section('title', 'Student Reports')

@section('styles')
<style>
/* Card styles */
.card-modern-purple {
    background: #6366F1;
    border-radius: 28px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff !important;
    transition: box-shadow 0.2s;
}
.card-modern-purple .fw-bold,
.card-modern-purple .display-6 { color: #fff; }

.card-modern-yellow {
    background: #fbbf24;
    border-radius: 28px;
    box-shadow: 0 4px 24px rgba(251,191,36,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff !important;
    transition: box-shadow 0.2s;
}
.card-modern-yellow .fw-bold,
.card-modern-yellow .display-6 { color: #fff; }

.card-modern-green {
    background: #22c55e;
    border-radius: 28px;
    box-shadow: 0 4px 24px rgba(16,185,129,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff !important;
    transition: box-shadow 0.2s;
}
.card-modern-green .fw-bold,
.card-modern-green .display-6 { color: #fff; }

/* Circular icon buttons */
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

.btn-icon.btn-delete {
    color: #ef4444;
    border-color: #ef4444;
}
.btn-icon.btn-delete:hover, .btn-icon.btn-delete:focus {
    background: #ef4444;
    color: #fff;
}
    .table thead th { 
        background: #0F172A; 
        color: #fff; 
    }
    .btn-success-custom {
    background: #22c55e;
    color: #fff !important;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 2rem;
    font-weight: 600;
    transition: background 0.2s;
}
.btn-success-custom:hover, .btn-success-custom:focus {
    background: #16a34a;
    color: #fff !important;
}

.btn-danger-custom {
    background: #ef4444;
    color: #fff !important;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 2rem;
    font-weight: 600;
    transition: background 0.2s;
}
.btn-danger-custom:hover, .btn-danger-custom:focus {
    background: #b91c1c;
    color: #fff !important;
}
.btn-icon.btn-edit.disabled,
.btn-icon.btn-edit:disabled {
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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0"><i class="bi bi-journal-text"></i>Report <span class="brand-highlight">List</span></h2>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-modern-purple text-center w-100 filter-card {{ !$feedback ? 'shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>null])) }}'">
            <div class="fw-bold mb-1" style="font-size:1.2rem;">
                <i class="bi bi-files" style="font-size:1.5rem;vertical-align:middle;"></i>
                All Reports
            </div>
            <div class="display-6">{{ $allCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern-yellow text-center w-100 filter-card {{ $feedback === 'notyet' ? 'shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>'notyet'])) }}'">
            <div class="fw-bold mb-1" style="font-size:1.2rem;">
                <i class="bi bi-hourglass-split" style="font-size:1.5rem;vertical-align:middle;"></i>
                Pending
            </div>
            <div class="display-6">{{ $notYetCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern-green text-center w-100 filter-card {{ $feedback === 'given' ? 'shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>'given'])) }}'">
            <div class="fw-bold mb-1" style="font-size:1.2rem;">
                <i class="bi bi-check-circle" style="font-size:1.5rem;vertical-align:middle;"></i>
                Given
            </div>
            <div class="display-6">{{ $givenCount }}</div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center mb-3" style="gap: 1rem;">
    <form method="get" class="d-flex align-items-center gap-2 flex-grow-1" style="flex-wrap: wrap;">
        <div class="input-group" style="max-width:300px;">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            <input type="hidden" name="feedback" value="{{ $feedback }}">
            <button class="btn" type="submit" style="background:#6366F1; color:#fff; border-color:#6366F1;">
                <i class="bi bi-search"></i> Search
            </button>
            @if(request('date'))
                <a href="?{{ http_build_query(array_merge(request()->except('date','page'))) }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
    <a href="{{ route('supervisor.university.students') }}" class="btn btn-danger-custom ms-auto">Back to Student List</a>
</div>

<div class="card card-modern p-4">
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
                @foreach($reports as $report)
                <tr>
                    <td>{{ $report->report_date }}</td>
                    <td>
                        @if($report->uni_feedback)
                        <span class="badge bg-success">Given</span>
                        @else
                        <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('supervisor.university.report.show', $report->id) }}" class="btn-icon btn-view" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button type="button"
                            class="btn-icon btn-edit edit-feedback-btn {{ !$report->uni_feedback ? 'disabled' : '' }}"
                            data-id="{{ $report->id }}"
                            data-feedback="{{ $report->uni_feedback ?? '' }}"
                            title="Edit Feedback"
                            {{ !$report->uni_feedback ? 'disabled' : '' }}>
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
 <div>
 {{ $reports->links('vendor.pagination.bootstrap-4') }}
 </div>
 <!-- Feedback Edit Modal -->
<div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editFeedbackForm" method="POST">
      @csrf
      <div class="modal-content card-modern">
        <div class="modal-header">
          <h5 class="modal-title" id="editFeedbackModalLabel"><i class="bi bi-pencil"></i> Edit Feedback</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
    <div id="noFeedbackMsg" class="alert alert-warning" style="display:none;">
        No feedback available to edit.
    </div>
    <textarea name="uni_feedback" id="feedbackTextarea" class="form-control" rows="4" required></textarea>
</div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success-custom">Save</button>
            <button type="button" class="btn btn-danger-custom" id="deleteFeedbackBtn">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFeedbackConfirmModal" tabindex="-1" aria-labelledby="deleteFeedbackConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header border-0">
        <h4 class="modal-title w-100 text-danger fw-bold" id="deleteFeedbackConfirmModalLabel">Confirm Deletion</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">Are you sure you want to delete this feedback?</div>
        <button type="button" class="btn btn-danger-custom px-4" id="confirmDeleteFeedbackBtn">Delete</button>
        <button type="button" class="btn btn-secondary ms-2 px-4" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let editModal = new bootstrap.Modal(document.getElementById('editFeedbackModal'));
    let feedbackForm = document.getElementById('editFeedbackForm');
    let feedbackTextarea = document.getElementById('feedbackTextarea');
    let deleteBtn = document.getElementById('deleteFeedbackBtn');
    let currentReportId = null;

    let deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteFeedbackConfirmModal'));
    let confirmDeleteBtn = document.getElementById('confirmDeleteFeedbackBtn');

    document.querySelectorAll('.edit-feedback-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentReportId = this.getAttribute('data-id');
            let feedback = this.getAttribute('data-feedback');
            feedbackTextarea.value = feedback;
            feedbackForm.action = `/supervisor/university/report/${currentReportId}/feedback`;
            if (feedback.trim()) {
                feedbackTextarea.style.display = '';
                deleteBtn.style.display = 'inline-block';
                feedbackTextarea.readOnly = false;
                document.getElementById('noFeedbackMsg').style.display = 'none';
            } else {
                feedbackTextarea.style.display = 'none';
                deleteBtn.style.display = 'none';
                document.getElementById('noFeedbackMsg').style.display = '';
            }
            editModal.show();
        });
    });

    deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        deleteConfirmModal.show();
    });

    confirmDeleteBtn.addEventListener('click', function() {
        fetch(`/supervisor/university/report/${currentReportId}/feedback`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => {
            deleteConfirmModal.hide();
            editModal.hide();
            // Show success message
            setTimeout(function() {
                let alert = document.createElement('div');
                alert.className = 'alert alert-success mt-3';
                alert.innerText = 'Feedback deleted successfully!';
                document.body.prepend(alert);
                setTimeout(() => alert.remove(), 2500);
                location.reload();
            }, 400);
        });
    });
});
</script>
@endpush
@endsection