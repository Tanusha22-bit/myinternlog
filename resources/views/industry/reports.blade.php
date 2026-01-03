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
    .btn-square {
    width: 40px;
    height: 40px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px !important;
    font-size: 1.3rem;
}
.btn-purple {
    background: #6366F1;
    color: #fff !important;
    border: none;
    transition: background 0.2s;
}
.btn-purple:hover { background: #4F46E5; }
.btn-grey {
    background: #bdbdbd;
    color: #fff !important;
    border: none;
}
.btn-grey:disabled, .btn-grey[disabled] {
    opacity: 0.7;
}
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    @php
        $statuses = [
            'all' => ['label' => 'All Reports', 'color' => 'primary', 'icon' => 'bi-clipboard-data'],
            'pending' => ['label' => 'Pending', 'color' => 'warning text-dark', 'icon' => 'bi-hourglass-split'],
            'given' => ['label' => 'Given', 'color' => 'success', 'icon' => 'bi-check-circle'],
        ];
        $currentStatus = $status;
    @endphp
    @foreach($statuses as $key => $info)
    <div class="col-md-4">
        <a href="{{ route('industry.reports', ['status' => $key]) }}" style="text-decoration:none;">
            <div class="card-modern p-3 text-center {{ $currentStatus == $key ? 'border border-'.$info['color'].' border-3' : '' }}">
                <div class="fw-bold mb-1 text-{{ $info['color'] }}">
                    <i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}
                </div>
                <div class="display-6 text-{{ $info['color'] }}">{{ $counts[$key] ?? 0 }}</div>
            </div>
        </a>
    </div>
    @endforeach
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
                        <span class="badge bg-{{ $report->industry_feedback ? 'success' : 'warning text-dark' }}">
                            {{ $report->industry_feedback ? 'Given' : 'Pending' }}
                        </span>
                    </td>
                    <td>
    <div class="d-flex gap-2">
        <a href="{{ route('industry.reports.show', $report->id) }}" class="btn btn-square btn-purple">
            <i class="bi bi-eye"></i>
        </a>
        @if($report->industry_feedback)
            <button class="btn btn-square btn-warning" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $report->id }}">
                <i class="bi bi-pencil"></i>
            </button>
        @else
            <button class="btn btn-square btn-grey" disabled>
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
                <button class="btn btn-danger px-5" type="button" onclick="if(confirm('Delete feedback?')){ this.form.industry_feedback.value=''; this.form.submit(); }">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- View Modal -->
    <div class="modal fade" id="viewReportModal{{ $report->id }}" tabindex="-1" aria-labelledby="viewReportModalLabel{{ $report->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content card-modern">
          <div class="modal-header">
            <h5 class="modal-title" id="viewReportModalLabel{{ $report->id }}">
                <i class="bi bi-eye"></i> Report Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <h5>
                    <i class="bi bi-person"></i>
                    <strong>{{ $report->student_name ?? 'Student' }}</strong>
                    <span class="text-muted">({{ $report->student_matric ?? '' }})</span>
                </h5>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong class="text-primary">Date:</strong> {{ $report->report_date }}
                    </div>
                    <div class="col-md-6">
                        <strong class="text-primary">Status:</strong>
                        @if($report->industry_feedback)
                            <span class="badge bg-success">Given</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </div>
                </div>
                <div class="mb-2">
                    <strong class="text-primary">Task:</strong> {{ $report->task }}
                </div>
                <div class="mb-2">
                    <strong class="text-primary">Attachment:</strong>
                    @if($report->file)
                        <a href="{{ asset('storage/'.$report->file) }}" target="_blank" class="text-success">
                            <i class="bi bi-paperclip"></i> Download file
                        </a>
                    @else
                        <span class="text-danger"><i class="bi bi-x-circle"></i> No file attached</span>
                    @endif
                </div>
                <div class="mb-2">
                    <strong>Supervisor Feedback:</strong>
                    <div class="border rounded p-2 bg-light">
                        {{ $report->industry_feedback ?? '-' }}
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
</div>
@endsection