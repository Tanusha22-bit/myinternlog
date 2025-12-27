@extends('layouts.university-dashboard')
@section('title', 'Student Reports')

@section('styles')
<style>
    .card-modern { 
        border-radius: 18px; 
        box-shadow: 0 2px 16px rgba(99,102,241,0.08); 
        background: #fff; 
    }
    .btn-indigo, .btn-danger-custom {
        border-radius: 999px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        border: none;
        transition: background 0.2s;
        font-size: 1rem;
    }
    .btn-indigo { 
        background: #6366F1; 
        color: #fff !important; 
    }
    .btn-indigo:hover { 
        background: #4F46E5; 
    }
    .btn-danger-custom {
        background: #EF4444;
        color: #fff !important;
    }
    .btn-pill {
        border-radius: 999px !important;
        font-weight: 500;
        padding: 0.5rem 1.5rem !important;
        border: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-danger-custom:hover { background: #DC2626; }
    .btn-warning-custom {
        background: #FBBF24;
        color: #0F172A !important;
    }
    .btn-warning-custom:hover { background: #F59E42; color: #fff !important; }
    .table thead th { 
        background: #0F172A; 
        color: #fff; 
    }
    .pagination {
        --bs-pagination-padding-x: 0.75rem;
        --bs-pagination-padding-y: 0.375rem;
        --bs-pagination-font-size: 1rem;
    }
    .pagination svg {
    width: 1em !important;
    height: 1em !important;
    max-width: 1em !important;
    max-height: 1em !important;
    min-width: 1em !important;
    min-height: 1em !important;
    font-size: 1em !important;
    vertical-align: middle !important;
    display: inline-block !important;
}
    .pagination svg.w-5, .pagination svg.h-5 {
    width: 1em !important;
    height: 1em !important;
    min-width: 1em !important;
    min-height: 1em !important;
    max-width: 1em !important;
    max-height: 1em !important;
}
.filter-card {
    transition: box-shadow 0.2s, border 0.2s;
    border: 2px solid transparent;
}
.filter-card:hover {
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    border-color: #6366F1;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Reports for <span class="brand-highlight">{{ $student->student_name }}</span></h2>
    <a href="{{ route('supervisor.university.students') }}" class="btn btn-danger-custom ms-2">Back to Student List</a>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ !$feedback ? 'border-primary shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>null])) }}'">
            <div class="fw-bold text-primary mb-1"><i class="bi bi-files"></i> All Reports</div>
            <div class="display-6">{{ $allCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ $feedback === 'notyet' ? 'border-warning shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>'notyet'])) }}'">
            <div class="fw-bold text-warning mb-1"><i class="bi bi-hourglass-split"></i> Pending</div>
            <div class="display-6">{{ $notYetCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ $feedback === 'given' ? 'border-success shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?{{ http_build_query(array_merge(request()->except('feedback','page'), ['feedback'=>'given'])) }}'">
            <div class="fw-bold text-success mb-1"><i class="bi bi-check-circle"></i> Given</div>
            <div class="display-6">{{ $givenCount }}</div>
        </div>
    </div>
</div>

<form method="get" class="mb-3">
    <div class="input-group" style="max-width:300px;">
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        <input type="hidden" name="feedback" value="{{ $feedback }}">
        <button class="btn btn-indigo" type="submit"><i class="bi bi-search"></i> Search</button>
        @if(request('date'))
            <a href="?{{ http_build_query(array_merge(request()->except('date','page'))) }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </div>
</form>
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
    <a href="{{ route('supervisor.university.report.show', $report->id) }}" class="btn btn-indigo btn-pill btn-sm" title="View">
        <i class="bi bi-eye"></i>
    </a>
    <button type="button"
        class="btn btn-warning-custom btn-pill btn-sm edit-feedback-btn"
        data-id="{{ $report->id }}"
        data-feedback="{{ $report->uni_feedback ?? '' }}"
        title="Edit Feedback"
        {{ empty($report->uni_feedback) ? 'disabled' : '' }}>
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
          <button type="submit" class="btn btn-indigo">Save</button>
          <button type="button" class="btn btn-danger-custom" id="deleteFeedbackBtn">Delete</button>
        </div>
      </div>
    </form>
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
        if (confirm('Are you sure you want to delete this feedback?')) {
            fetch(`/supervisor/university/report/${currentReportId}/feedback`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => location.reload());
        }
    });
});
</script>
@endpush
@endsection