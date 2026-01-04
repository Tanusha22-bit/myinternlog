@extends('layouts.student-dashboard')

@section('styles')
    <style>
.card-modern {
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    background: #fff;
}
.card-modern:hover {
    background: #6366F1 !important;
    color: #fff !important;
    box-shadow: 0 4px 24px rgba(99,102,241,0.15);
}
.card-modern:hover .badge,
.card-modern:hover .progress-bar,
.card-modern:hover strong,
.card-modern:hover .fw-bold {
    color: #fff !important;
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
.form-label {
    font-weight: bold;
}
 </style>
@endsection

@section('title', 'Daily Report')
@section('page-title')
    <h2 class="mb-0">
        <i class="bi-journal-text me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Daily<span style="color:#6366F1;">Log</span></span>
    </h2>
@endsection

@section('content')

<div class="row g-4 mb-4">
    <!-- Today's Log Status Card -->
    <div class="col-md-6 d-flex">
        <div class="card card-modern p-3 w-100 h-100">
            <div class="fw-bold mb-2"><i class="bi bi-calendar-check"></i> Today's Log Status</div>
            <div>
                <strong>Date:</strong> {{ $today->format('d M Y') }}<br>
                <strong>Status:</strong>
                @if($todaysLog)
                    <span class="badge bg-success">Submitted</span>
                @else
                    <span class="badge bg-danger">Not Submitted</span>
                @endif
            </div>
            <div class="mt-2">
                <strong>Last Submission:</strong>
                @if($lastSubmission)
                    {{ \Carbon\Carbon::parse($lastSubmission->report_date)->format('d M Y') }}
                @else
                    <span class="text-muted">No submission yet</span>
                @endif
            </div>
        </div>
    </div>
    <!-- Progress Bar Card -->
    <div class="col-md-6 d-flex">
        <div class="card card-modern p-3 w-100 h-100">
            <div class="fw-bold mb-2"><i class="bi bi-bar-chart-steps"></i> Progress</div>
            <div class="mb-2">
                <strong>{{ $submittedDays }}</strong> / <strong>{{ $totalWorkingDays }}</strong> reports submitted
            </div>
            <div class="progress" style="height: 22px;">
                <div class="progress-bar bg-success" role="progressbar"
                     style="width: {{ $totalWorkingDays ? round(($submittedDays/$totalWorkingDays)*100) : 0 }}%;">
                    {{ $totalWorkingDays ? round(($submittedDays/$totalWorkingDays)*100) : 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daily Report Form -->
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('daily-report.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="report_date" class="form-label">Date</label>
                    <input type="date" name="report_date" id="report_date" class="form-control" required min="{{ $internship->start_date }}"
                        max="{{ $internship->end_date }}"
                        onkeydown="return false"
                        onchange="validateWeekday(this)">
                    <script>
                        function validateWeekday(input) {
                        const date = new Date(input.value);
                        const day = date.getDay();
                            if (day === 0 || day === 6) { // Sunday=0, Saturday=6
                                alert('Please select a weekday (Monday to Friday).');
                                input.value = '';
                            }
                        }
                    </script>
                </div>
                <div class="mb-3">
                    <label for="task" class="form-label">Log</label>
                    <textarea name="task" id="task" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.png,.jpeg,.jpg,.doc,.docx">
                    <div class="form-text text-danger" style="font-weight:500;">
                        Allowed: PDF, PNG, JPEG, JPG, DOC, DOCX. Max size: 10MB.
                    </div>
                </div>
                <button class="btn btn-success-custom w-100 mb-2" type="submit">Submit</button>
                    <a href="{{ route('daily-report.pdf_preview') }}" class="btn btn-indigo w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Generate PDF Logbook
                    </a>
            </form>
        </div>
    </div>
</div>
@endsection