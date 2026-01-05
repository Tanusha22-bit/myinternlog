{{-- filepath: resources/views/daily_reports/create.blade.php --}}
@extends('layouts.student-dashboard')

@section('styles')
    <style>
               .btn-success-custom {
            background: #22c55e;
            color: #fff;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-success-custom:hover {
            background: #16a34a;
            color: #fff;
        }
        .btn-indigo {
            background: #6366F1;
            color: #fff !important;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-indigo:hover, .btn-indigo:focus {
            background: #4F46E5;
            color: #fff !important;
        }
        /* Blinking animation for Not Submitted badge */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }
        .badge-blink {
            animation: blink 1s linear infinite;
        }
        @media (min-width: 992px) {
            .custom-flex-row {
                display: flex;
                gap: 2rem;
                align-items: flex-start;
            }
            .form-col {
                flex: 1 1 45%;
            }
            .status-col {
                flex: 1 1 55%;
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
        }
        .reminder-marquee {
    background: #fff3cd;
    color: #856404;
    border-radius: 8px;
    padding: 0.7rem 0;
    font-weight: 600;
    font-size: 1.08rem;
    overflow: hidden;
    position: relative;
    margin-bottom: 1rem;
    border: 1px solid #ffeeba;
}
.reminder-marquee span {
    display: inline-block;
    white-space: nowrap;
    animation: marquee 12s linear infinite;
}
@keyframes marquee {
    0%   { transform: translateX(100%);}
    100% { transform: translateX(-100%);}
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

<div class="reminder-marquee">
    <span>
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Reports can be submitted only between the start and end date of the internship!!!
    </span>
</div>

@if(!$internship)
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-info text-center p-4" style="font-size:1.15rem; background: #c7f3ff;">
                <i class="bi bi-info-circle" style="font-size:2rem;color:#6366F1;"></i>
                <div class="mt-2 fw-bold">No Active Internship Assigned</div>
                <div class="mt-1">You do not have an active internship assigned yet.<br>
                Please contact your administrator or university supervisor for assistance.</div>
            </div>
        </div>
    </div>
@else
    <div class="custom-flex-row">
        <!-- Form Card (Left) -->
        <div class="form-col mb-4">
            <div class="card p-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                        </div>
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
        <!-- Status/Progress Cards (Right, stacked) -->
<div class="status-col mb-4">
    <!-- Today's Log Status Card -->
    <div class="card card-modern shadow-sm p-4 w-100 h-100 mb-4" style="background: #f7f8fa; border-radius: 18px;">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-calendar-check" style="font-size:1.5rem; color:#6366F1;"></i>
            <span class="fw-bold ms-2" style="font-size:1.15rem;">Today's Log Status</span>
        </div>
        <div class="mb-2">
            <span class="fw-semibold text-secondary">Date:</span>
            <span>{{ $today->format('d M Y') }}</span>
        </div>
        <div class="mb-2">
            <span class="fw-semibold text-secondary">Status:</span>
            @if($todaysLog)
                <span class="badge bg-success badge-blink ms-1">Submitted</span>
            @else
                <span class="badge bg-danger badge-blink ms-1">Not Submitted</span>
            @endif
        </div>
        <div>
            <span class="fw-semibold text-secondary">Last Submission:</span>
            @if($lastSubmission)
                <span>{{ \Carbon\Carbon::parse($lastSubmission->report_date)->format('d M Y') }}</span>
            @else
                <span class="text-muted">No submission yet</span>
            @endif
        </div>
    </div>
    <!-- Progress Bar Card -->
    <div class="card card-modern shadow-sm p-4 w-100 h-100" style="background: #f7f8fa; border-radius: 18px;">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-bar-chart-steps" style="font-size:1.5rem; color:#6366F1;"></i>
            <span class="fw-bold ms-2" style="font-size:1.15rem;">Progress</span>
        </div>
        <div class="mb-2">
            <span class="fw-semibold" style="color:#222;">{{ $submittedDays }}</span>
            <span class="text-secondary">/</span>
            <span class="fw-semibold" style="color:#222;">{{ $totalWorkingDays }}</span>
            <span class="text-secondary">reports submitted</span>
        </div>
        <div class="progress" style="height: 22px; background: #e9ecef;">
            <div class="progress-bar bg-success" role="progressbar"
                style="width: {{ $totalWorkingDays ? round(($submittedDays/$totalWorkingDays)*100) : 0 }}%;">
                {{ $totalWorkingDays ? round(($submittedDays/$totalWorkingDays)*100) : 0 }}%
            </div>
        </div>
    </div>
</div>
    </div>
@endif
@endsection