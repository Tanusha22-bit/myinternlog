@extends('layouts.industry-dashboard')
@section('title', 'Industry Supervisor Dashboard')


@section('styles')
<style>
    .progress-bar-purple {
        background-color: #6366F1 !important;
    }
</style>
@endsection

@section('content')

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-steps"></i> Student Progress</h5>
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
                    <div class="progress mb-2" style="height: 18px;">
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
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event"></i> Important Dates</h5>
            @if($importantDates->count())
                <ul class="mb-0">
                    @foreach($importantDates as $date)
                        <li>
                            <strong>{{ $date->date }}</strong>: {{ $date->title }}
                        </li>
                    @endforeach
                </ul>
            @else
                <div>No important dates.</div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-text"></i> Recent Reports</h5>
            @if($recentReports && count($recentReports))
                <ul class="mb-0">
                    @foreach($recentReports as $report)
                        <li>
                            <strong>{{ $report->report_date }}</strong> - {{ \Illuminate\Support\Str::limit($report->task, 30) }}
                            <span class="badge {{ $report->industry_feedback ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $report->industry_feedback ? 'Feedback Given' : 'Pending' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div>No reports yet.</div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-megaphone"></i> Announcements</h5>
            @if($announcements->count())
                <ul class="mb-0">
                    @foreach($announcements as $announcement)
                        <li>
                            <strong>{{ $announcement->title }}</strong>
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