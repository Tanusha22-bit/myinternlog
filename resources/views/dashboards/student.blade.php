@extends('layouts.student-dashboard')

@section('title', 'Student Dashboard')

@section('styles')
<style>
.progress {
    background: #e0e7ff;
    border-radius: 999px;
    overflow: hidden;
}
.progress-bar {
    background: #6366F1;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection

@section('content')
<div class="row g-4 align-items-stretch">
    <!-- Reports Submitted Card -->
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 h-100">
            <div class="fw-bold mb-1"><i class="bi bi-journal-text"></i> Reports Submitted</div>
            <div class="display-6 text-success">{{ $reportsThisMonth }}</div>
            <div class="text-muted">This Month / {{ $reportsOverall }} Overall</div>
        </div>
    </div>
    <!-- Tasks Status Card -->
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 h-100">
            <div class="fw-bold mb-1"><i class="bi bi-list-task"></i> Tasks</div>
            <div>
                <span class="badge bg-warning text-dark">Pending: {{ $tasksPending }}</span>
                <span class="badge bg-info text-dark">In Progress: {{ $tasksInProgress }}</span>
                <span class="badge bg-success">Completed: {{ $tasksCompleted }}</span>
            </div>
        </div>
    </div>
    <!-- Internship Progress Bar Card -->
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 h-100">
            <div class="fw-bold mb-1"><i class="bi bi-graph-up-arrow"></i> Internship Progress</div>
            <div class="progress" style="height: 28px; background: #e0e7ff;">
                <div class="progress-bar" role="progressbar"
                     style="width: {{ $progressPercent }}%; font-size:1.1rem; background: #6366F1;"
                     aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $progressPercent }}%
                </div>
            </div>
            <div class="text-muted mt-1">Week {{ $currentWeek }} of {{ $totalWeeks }}</div>
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
    {{-- Calendar --}}
    <div class="col-lg-6">
        <div class="card card-modern p-4 h-100">
            <div class="fw-bold mb-3"><i class="bi bi-calendar-event"></i> Important Dates</div>
            {{-- Simple calendar table, replace with JS calendar if needed --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($importantDates as $event)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
                            <td>{{ $event->title }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No important dates.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Downloadable Documents --}}
    <div class="col-lg-6">
        <div class="card card-modern p-4 h-100">
            <div class="fw-bold mb-3"><i class="bi bi-download"></i> Downloadable Documents</div>
            <ul class="list-group">
                @forelse($documents as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-earmark-pdf"></i> {{ $doc->title }}</span>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" class="btn btn-indigo btn-sm" target="_blank">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No documents available.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    {{-- Supervisors Contact Card --}}
    <div class="col-md-4">
        <div class="card card-modern p-4 h-100">
            <div class="fw-bold mb-3"><i class="bi bi-person-lines-fill"></i> Supervisors Contact</div>
            <div class="mb-2">
                <span class="fw-bold">Industry Supervisor:</span><br>
                {{ $industrySupervisor->user->name ?? '-' }}<br>
                <i class="bi bi-envelope"></i> {{ $industrySupervisor->user->email ?? '-' }}<br>
                <i class="bi bi-telephone"></i> {{ $industrySupervisor->phone ?? '-' }}
            </div>
            <div>
                <span class="fw-bold">University Supervisor:</span><br>
                {{ $universitySupervisor->user->name ?? '-' }}<br>
                <i class="bi bi-envelope"></i> {{ $universitySupervisor->user->email ?? '-' }}<br>
                <i class="bi bi-telephone"></i> {{ $universitySupervisor->phone ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="col-md-4">
        <div class="card card-modern p-4 h-100">
            <div class="fw-bold mb-3"><i class="bi bi-activity"></i> Activity Feed</div>
            <ul class="list-group">
                @forelse($activities as $activity)
                    <li class="list-group-item">
                        {!! $activity->description !!}
                        <br>
                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No recent activity.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Recent Announcements --}}
    <div class="col-md-4">
        <div class="card card-modern p-4 h-100">
            <div class="fw-bold mb-3"><i class="bi bi-megaphone"></i> Announcements</div>
            <ul class="list-group">
                @forelse($announcements as $notice)
                    <li class="list-group-item">
                        <span class="fw-bold">{{ $notice->title }}</span>
                        <br>
                        <small class="text-muted">{{ $notice->created_at->format('d M Y') }}</small>
                        <div>{{ \Illuminate\Support\Str::limit($notice->content, 60) }}</div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No announcements.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection