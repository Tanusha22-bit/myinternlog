@extends('layouts.university-dashboard')
@section('title','University Supervisor Dashboard')

@section('styles')
<style>
    .dashboard-card {
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(99,102,241,0.08);
        color: #22223b;
        padding: 2rem 1.5rem;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .bg-indigo { background: #6366F1; color: #fff !important; }
    .bg-green { background: #22C55E; color: #fff !important; }
    .bg-blue { background: #0EA5E9; color: #fff !important; }
    .bg-yellow { background: #FACC15; color: #92400E !important; }
    .bg-light-indigo { background: #EEF2FF; }
    .bg-light-green { background: #ECFDF5; }
    .bg-light-blue { background: #F0F9FF; }
    .dashboard-card .display-6 {
        font-size: 2.2rem;
        font-weight: 700;
    }
    .dashboard-card .fw-bold {
        font-size: 1.1rem;
    }
    .dashboard-card .badge {
        font-size: 1rem;
        vertical-align: middle;
    }
    .dashboard-btn {
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.7rem 0;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
        border: none;
    }
    .dashboard-btn-outline {
        border: 2px solid #6366f1;
        color: #6366f1 !important;
        background: #fff;
    }
    .dashboard-btn-outline:hover {
        background: #6366f1;
        color: #fff !important;
    }
    .dashboard-btn-outline-blue {
        border: 2px solid #0EA5E9;
        color: #0EA5E9 !important;
        background: #fff;
    }
    .dashboard-btn-outline-blue:hover {
        background: #0EA5E9;
        color: #fff !important;
    }
    .list-group-item {
        border: none;
        padding-left: 0;
        padding-right: 0;
        background: transparent;
    }
    .dashboard-link {
        color: inherit;
        text-decoration: underline;
        font-weight: 500;
    }
    .dashboard-link:hover {
        color: #6366F1;
    }
    .dashboard-btn {
    border-radius: 999px;
    font-weight: 600;
    font-size: 1.08rem;
    padding: 0.7rem 0;
    margin-bottom: 0.5rem;
    transition: background 0.2s;
    border: none;
    width: 100%;
    text-align: center;
    display: block;
    text-decoration: none !important;
}
.dashboard-btn.bg-green {
    background: #22C55E;
    color: #fff !important;
}
.dashboard-btn.bg-green:hover { background: #16a34a; }
.dashboard-btn.bg-blue {
    background: #0EA5E9;
    color: #fff !important;
}
.dashboard-btn.bg-blue:hover { background: #0369a1; }
.dashboard-btn.bg-yellow {
    background: #FACC15;
    color: #92400E !important;
}
.dashboard-btn.bg-yellow:hover { background: #eab308; color: #fff !important; }
.dashboard-btn-outline {
    border: 2px solid #6366f1;
    color: #6366f1 !important;
    background: #fff;
}
.dashboard-btn-outline:hover {
    background: #6366f1;
    color: #fff !important;
}
.dashboard-btn-outline-blue {
    border: 2px solid #0EA5E9;
    color: #0EA5E9 !important;
    background: #fff;
}
.dashboard-btn-outline-blue:hover {
    background: #0EA5E9;
    color: #fff !important;
}
.blink-new {
    background: #ef4444;
    color: #fff;
    border-radius: 8px;
    padding: 0.15em 0.7em;
    font-size: 0.85em;
    font-weight: 700;
    margin-left: 0.5em;
    animation: blink 1s steps(2, start) infinite;
    vertical-align: middle;
}
@keyframes blink {
    to { visibility: hidden; }
}
.badge-small {
    background: #6366F1;
    color: #fff;
    font-size: 0.8em;
    padding: 0.18em 0.7em;
    border-radius: 8px;
    font-weight: 600;
    vertical-align: middle;
}
.badge-pending {
    background: #fbbf24;
    color: #92400E;
    font-size: 0.8em;
    padding: 0.18em 0.7em;
    border-radius: 8px;
    font-weight: 600;
    vertical-align: middle;
    margin-left: 1em;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0"><i class="bi bi-house-door-fill"></i> Supervisor <span class="brand-highlight">Dashboard</span></h2>
</div>

<div class="row g-4 mb-4">
    <!-- Analytics Cards (still 4 in a row for summary) -->
    <div class="col-md-3 d-flex">
        <div class="dashboard-card bg-indigo flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-people"></i> Total Students</div>
            <div class="display-6">{{ $totalStudents }}</div>
        </div>
    </div>
    <div class="col-md-3 d-flex">
        <div class="dashboard-card bg-green flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-briefcase"></i> Active Internships</div>
            <div class="display-6">{{ $activeInternships }}</div>
        </div>
    </div>
    <div class="col-md-3 d-flex">
        <div class="dashboard-card bg-blue flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-check-circle"></i> Completed Internships</div>
            <div class="display-6">{{ $completedInternships }}</div>
        </div>
    </div>
    <div class="col-md-3 d-flex">
        <div class="dashboard-card bg-yellow flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-journal-text"></i> Reports This Month</div>
            <div class="display-6">{{ $reportsThisMonth }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Student Overview -->
    <div class="col-md-6 d-flex">
        <div class="dashboard-card bg-light-indigo flex-fill w-100">
            <div class="fw-bold mb-2"><i class="bi bi-people"></i> Student Overview</div>
            <ul class="list-group list-group-flush mb-3">
                @php $activeStudents = $studentSummary->where('internship_status', 'active'); @endphp
                @forelse($activeStudents as $student)
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <span>
                            <span class="fw-semibold">{{ $student->student_id }}</span>
                            <span class="text-muted">- {{ $student->program }}</span>
                        </span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No active students.</li>
                @endforelse
            </ul>
            <a href="{{ route('supervisor.university.students') }}" class="dashboard-btn dashboard-btn-outline">View All Students</a>
        </div>
    </div>
    <!-- Reports Haven't Viewed -->
    <div class="col-md-6 d-flex">
        <div class="dashboard-card bg-light-blue flex-fill w-100">
            <div class="fw-bold mb-2"><i class="bi bi-eye-slash"></i> Reports Haven't Viewed</div>
            <ul class="list-group list-group-flush mb-3">
                @forelse($recentReports as $report)
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <span>{{ $report->student_name }} - {{ $report->report_date }}</span>
                        <span class="badge badge-pending">Pending</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">All reports reviewed!</li>
                @endforelse
            </ul>
            <a href="{{ route('supervisor.university.students') }}" class="dashboard-btn dashboard-btn-outline-blue">View All Reports</a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Announcements -->
    <div class="col-md-6 d-flex">
        <div class="dashboard-card bg-light-green flex-fill w-100">
            <div class="fw-bold mb-2"><i class="bi bi-megaphone"></i> Announcements</div>
            <ul class="list-group list-group-flush mb-3">
                @forelse($announcements as $i => $announcement)
                    <li class="list-group-item">
                        <strong>{{ $announcement->title }}</strong>
                        @if($i === 0)
                            <span class="blink-new">NEW</span>
                        @endif
                        <div style="font-size:0.95em;">{!! \Illuminate\Support\Str::limit(strip_tags($announcement->content), 60) !!}</div>
                        <div class="text-muted" style="font-size:0.85em;">{{ \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') }}</div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No announcements.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <!-- Important Dates -->
    <div class="col-md-6 d-flex">
        <div class="dashboard-card bg-light-blue flex-fill w-100">
            <div class="fw-bold mb-2"><i class="bi bi-calendar-event"></i> Important Dates</div>
            <ul class="list-group list-group-flush mb-3">
                @forelse($importantDates as $i => $date)
                    <li class="list-group-item">
                        <strong>{{ $date->title }}</strong>
                        @if($i === 0)
                            <span class="blink-new">NEW</span>
                        @endif
                        <div class="text-muted" style="font-size:0.95em;">
                            {{ \Carbon\Carbon::parse($date->date)->format('d M Y') }}
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No important dates.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection