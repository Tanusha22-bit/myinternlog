@extends('layouts.student-dashboard')

@section('styles')
<style>
.form-label {
    font-weight: bold;
}
.btn-success-custom {
    background: #22C55E;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-success-custom:hover, .btn-success-custom:focus {
    background: #16a34a;
    color: #fff;
}
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
.form-check-input.red-check {
    accent-color: #EF4444; /* Modern browsers support this for checkbox color */
}
.form-check-label.text-danger {
    color: #EF4444 !important;
    font-weight: 500;
}
</style>
@endsection

@section('title', 'Edit Report')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Edit<span class="brand-highlight">Report</span></h2>
</div>
<div class="card card-modern p-4 mb-3">
    <form method="POST" action="{{ route('daily-report.update', $report->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="report_date" class="form-label">Date</label>
        <input type="text" id="report_date" class="form-control" value="{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}" readonly>
    </div>
    <div class="mb-3">
        <label for="task" class="form-label">Log</label>
        <textarea name="task" id="task" class="form-control" rows="4" required>{{ $report->task }}</textarea>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Attachment (PDF, PNG, JPEG, DOCX, max 10MB)</label>
        @if($report->file)
            <div class="mb-2">
                <a href="{{ asset('storage/' . $report->file) }}" target="_blank" class="btn btn-indigo btn-sm">
                    <i class="bi bi-paperclip"></i> View Current File
                </a>
                <div class="form-check mt-1">
    <input class="form-check-input red-check" type="checkbox" name="delete_file" id="delete_file">
    <label class="form-check-label text-danger" for="delete_file">Delete current file</label>
</div>
            </div>
        @endif
        <input type="file" name="file" id="file" class="form-control"
            accept=".pdf,.png,.jpeg,.jpg,.doc,.docx">
    </div>
    <button class="btn btn-success-custom" type="submit">Update</button>
    <a href="{{ route('daily-report.show', $report->id) }}" class="btn btn-indigo ms-2">Cancel</a>
</form>
</div>
@endsection