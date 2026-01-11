@extends('layouts.industry-dashboard')
@section('title', 'Industry Supervisor Dashboard')


@section('styles')
<style>
.card-modern {
    border-radius: 22px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    background: #fff;
    min-height: 260px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 2rem 2rem 1.5rem 2rem;
    margin-bottom: 0;
}
.dashboard-row {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    margin-bottom: 2rem;
}
.dashboard-col {
    flex: 1 1 350px;
    min-width: 320px;
    max-width: 100%;
    display: flex;
}
.card-modern h5 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
    letter-spacing: 0.01em;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-modern ul, .card-modern li {
    font-size: 1.07rem;
}
.blink-badge {
    display: inline-block;
    background: #ef4444;
    color: #fff;
    font-size: 0.85rem;
    font-weight: bold;
    border-radius: 999px;
    padding: 2px 12px;
    margin-left: 8px;
    animation: blink 1s steps(2, start) infinite;
    vertical-align: middle;
}
@keyframes blink {
    to { visibility: hidden; }
}
.progress {
    background: #e5e7eb;
    border-radius: 8px;
    height: 18px;
}
.progress-bar-purple {
    background-color: #6366F1 !important;
    font-weight: 600;
    font-size: 1rem;
}
.card-modern li strong {
    font-weight: 600;
}
.card-modern .badge {
    font-size: 0.95em;
    vertical-align: middle;
}
.card-modern .badge {
    font-size: 1em;
    vertical-align: middle;
    padding: 6px 16px;
    margin-left: 14px;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.01em;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.badge.bg-success {
    background: #15803d !important;
    color: #fff !important;
}
.badge.bg-warning {
    background: #fbbf24 !important;
    color: #92400E !important;
}
.badge.blink-pending {
    animation: blink 1s steps(2, start) infinite;
}
.badge-small {
    font-size: 0.85em !important;
    padding: 3px 12px !important;
    border-radius: 8px !important;
    font-weight: 600;
    margin-left: 10px;
}
@keyframes blink {
    to { visibility: hidden; }
}
</style>
@endsection

@section('content')
<div class="dashboard-row">
    <div class="dashboard-col">
        <div class="card-modern w-100">
            <h5><i class="bi bi-bar-chart-steps"></i> Student Progress</h5>
            @if($internship)
                <div>
                    <div class="mb-2"><strong>Reports Submitted:</strong> {{ $totalReports }}</div>
                    <div class="mb-2"><strong>Feedback Given:</strong> {{ $reportsWithFeedback }}</div>
                    <div class="mb-2">
                        <strong>Last Report:</strong>
                        @if($lastReport)
                            {{ $lastReport->report_date }} ({{ $lastReport->industry_feedback ? 'Feedback Given' : 'Pending' }})
                        @else
                            No reports yet.
                        @endif
                    </div>
                    <div class="progress mb-2">
                        <div class="progress-bar progress-bar-purple" role="progressbar"
                             style="width: {{ $totalReports ? ($reportsWithFeedback/$totalReports)*100 : 0 }}%;">
                            {{ $totalReports ? round(($reportsWithFeedback/$totalReports)*100) : 0 }}%
                        </div>
                    </div>
                </div>
            @else
                <div>No student assigned.</div>
            @endif
        </div>
    </div>
    <div class="dashboard-col">
        <div class="card-modern w-100">
            <h5><i class="bi bi-calendar-event"></i> Important Dates</h5>
            @if($importantDates->count())
                <ul class="mb-0">
                    @foreach($importantDates as $i => $date)
                        <li>
                            <strong>{{ $date->date }}</strong>: {{ $date->title }}
                            @if($i === 0)
                                <span class="blink-badge">NEW</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div>No important dates.</div>
            @endif
        </div>
    </div>
</div>
<div class="dashboard-row">
    <div class="dashboard-col">
        <div class="card-modern w-100">
            <h5><i class="bi bi-file-earmark-text"></i> Recent Reports</h5>
            @if($recentReports && count($recentReports))
                <ul class="mb-0">
                    @foreach($recentReports as $report)
    <li>
        <strong>{{ $report->report_date }}</strong> - {{ \Illuminate\Support\Str::limit($report->task, 30) }}
        @if($report->industry_feedback)
            <span class="badge bg-success badge-small">Given</span>
        @else
            <span class="badge bg-warning badge-small">Pending</span>
        @endif
    </li>
@endforeach
                </ul>
            @else
                <div>No reports yet.</div>
            @endif
        </div>
    </div>
    <div class="dashboard-col">
        <div class="card-modern w-100">
            <h5><i class="bi bi-megaphone"></i> Announcements</h5>
            @if($announcements->count())
                <ul class="mb-0">
                    @foreach($announcements as $i => $announcement)
                        <li>
                            <strong>{{ $announcement->title }}</strong>
                            @if($i === 0)
                                <span class="blink-badge">NEW</span>
                            @endif
                            <div style="font-size: 0.95em;">{!! \Illuminate\Support\Str::limit($announcement->content, 60) !!}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div>No announcements.</div>
            @endif
        </div>
    </div>
</div>
@endsection