@extends('layouts.student-dashboard')

@section('title', 'Student Dashboard')
@section('page-title')
    <h2 class="mb-0">
        <i class="bi bi-speedometer2 me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Student<span style="color:#6366F1;">Dashboard</span></span>
    </h2>
@endsection

@section('styles')
<style>
.card-modern {
    border: none;
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    background: var(--bg-card, #fff);
}
.card-modern .card-title {
    font-weight: bold;
    font-size: 1.15rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-modern .icon {
    font-size: 1.3rem;
    color: var(--primary, #6366F1);
}
.list-group-item {
    border: none;
    border-bottom: 1px solid #f3f4f6;
}
.list-group-item:last-child {
    border-bottom: none;
}
.timeline-bar {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}
.timeline-segment {
    width: 32px;
    height: 8px;
    margin-right: 4px;
    border-radius: 4px;
    background: #E5E7EB;
    opacity: 0.7;
    transition: background 0.2s, opacity 0.2s;
}
.timeline-segment.completed {
    background: #6366F1;
    opacity: 1;
}
.timeline-segment.current {
    background: #FBBF24;
    opacity: 1;
}
.timeline-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
}
.timeline-labels span {
    width: 32px;
    text-align: center;
}
/* Bigger badges for tasks */
.big-badge {
    font-size: 1.1rem;
    padding: 0.6em 1.2em;
    border-radius: 1.5em;
    margin: 0 0.2em 0.2em 0;
    font-weight: 600;
    vertical-align: middle;
}
/* Supervisor contact styling */
.supervisor-block {
    margin-bottom: 1.2em;
    padding-bottom: 0.8em;
    border-bottom: 1px solid #f3f4f6;
}
.supervisor-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.supervisor-title {
    font-weight: 600;
    color: #6366F1;
    margin-bottom: 0.3em;
}
.supervisor-detail {
    margin-left: 1.5em;
    margin-bottom: 0.2em;
    color: #222;
}
.supervisor-icon {
    margin-right: 0.5em;
    color: #6366F1;
}
/* Blinking NEW badge */
.blink-new {
    display: inline-block;
    background: #dc2626;
    color: #fff;
    font-size: 0.8rem;
    font-weight: bold;
    border-radius: 8px;
    padding: 2px 8px;
    margin-left: 8px;
    animation: blink 1s steps(2, start) infinite;
}
@keyframes blink {
    to {
        visibility: hidden;
    }
}
</style>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Reports Submitted Card -->
    <div class="col-md-6">
        <div class="card card-modern text-center p-4 h-100">
            <div class="card-title"><i class="bi bi-journal-text icon"></i> Reports Submitted</div>
            <div class="display-6 text-success">{{ $reportsThisMonth }}</div>
            <div class="text-muted">This Month / {{ $reportsOverall }} Overall</div>
        </div>
    </div>
    <!-- Tasks Status Card -->
    <div class="col-md-6">
        <div class="card card-modern text-center p-4 h-100">
            <div class="card-title"><i class="bi bi-list-task icon"></i> Tasks</div>
            <div>
                <span class="badge bg-warning text-dark big-badge">Pending: {{ $tasksPending }}</span>
                <span class="badge bg-info text-dark big-badge">In Progress: {{ $tasksInProgress }}</span>
                <span class="badge bg-success big-badge">Completed: {{ $tasksCompleted }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Internship Progress Timeline --}}
<div class="card card-modern p-4 mt-4 mb-4">
    <div class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Internship Progress Timeline</div>
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        @for($w = 1; $w <= $totalWeeks; $w++)
            <div style="flex:1; text-align:center;">
                <div style="height:12px; border-radius:6px; margin-bottom:4px;
                    background: {{ $w < $currentWeek ? '#6366F1' : ($w == $currentWeek ? '#FBBF24' : '#e0e7ff') }};
                    transition: background 0.2s;">
                </div>
                <small style="font-size:0.85rem; color:{{ $w == $currentWeek ? '#FBBF24' : '#888' }};">
                    W{{ $w }}
                </small>
            </div>
        @endfor
    </div>
</div>

<div class="row g-4">
    <!-- Important Dates -->
    <div class="col-lg-6">
        <div class="card card-modern p-4 h-100">
            <div class="card-title"><i class="bi bi-calendar-event icon"></i> Important Dates</div>
            @if($importantDates->count())
                @php
                    $latestDate = $importantDates->max('date');
                @endphp
                @foreach($importantDates as $event)
                    <div class="mb-3">
                        <span class="fw-bold">{{ $event->title }}
                            @if($event->date == $latestDate)
                                <span class="blink-new">NEW</span>
                            @endif
                        </span>
                        <div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</small>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-muted text-center">No important dates.</div>
            @endif
        </div>
    </div>
    <!-- Announcements -->
    <div class="col-lg-6">
        <div class="card card-modern p-4 h-100">
            <div class="card-title"><i class="bi bi-megaphone icon"></i> Announcements</div>
            @if($announcements->count())
                @php
                    $latestAnnouncement = $announcements->max('created_at');
                @endphp
                @foreach($announcements as $notice)
                    <div class="mb-3" style="border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span class="fw-bold">{{ $notice->title }}
                            @if($notice->created_at == $latestAnnouncement)
                                <span class="blink-new">NEW</span>
                            @endif
                        </span>
                        <div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($notice->created_at)->format('d M Y') }}</small>
                        </div>
                        <div>{{ \Illuminate\Support\Str::limit($notice->content, 60) }}</div>
                    </div>
                @endforeach
            @else
                <div class="text-muted text-center">No announcements.</div>
            @endif
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <!-- Supervisor Contact -->
    <div class="col-md-6">
        <div class="card card-modern p-4 h-100">
            <div class="card-title"><i class="bi bi-person-lines-fill icon"></i> Supervisors Contact</div>
            <div class="supervisor-block">
                <div class="supervisor-title"><i class="bi bi-person-badge supervisor-icon"></i>Industry Supervisor</div>
                <div class="supervisor-detail"><i class="bi bi-person"></i> {{ $industrySupervisor->user->name ?? '-' }}</div>
                <div class="supervisor-detail"><i class="bi bi-envelope"></i> {{ $industrySupervisor->user->email ?? '-' }}</div>
                <div class="supervisor-detail"><i class="bi bi-telephone"></i> {{ $industrySupervisor->phone ?? '-' }}</div>
            </div>
            <div class="supervisor-block">
                <div class="supervisor-title"><i class="bi bi-mortarboard supervisor-icon"></i>University Supervisor</div>
                <div class="supervisor-detail"><i class="bi bi-person"></i> {{ $universitySupervisor->user->name ?? '-' }}</div>
                <div class="supervisor-detail"><i class="bi bi-envelope"></i> {{ $universitySupervisor->user->email ?? '-' }}</div>
                <div class="supervisor-detail"><i class="bi bi-telephone"></i> {{ $universitySupervisor->phone ?? '-' }}</div>
            </div>
        </div>
    </div>
    <!-- Downloadable Documents -->
    <div class="col-md-6">
        <div class="card card-modern p-4 h-100">
            <div class="card-title"><i class="bi bi-download icon"></i> Downloadable Documents</div>
            @if($documents->count())
            <ul class="list-group">
                @foreach($documents as $doc)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-pdf"></i> {{ $doc->title }}</span>
                    <a href="{{ asset('storage/' . $doc->file_path) }}" class="btn btn-indigo btn-sm" target="_blank">
                        <i class="bi bi-download"></i> Download
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <div class="text-muted text-center">No documents available.</div>
            @endif
        </div>
    </div>
</div>
@endsection