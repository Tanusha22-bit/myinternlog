@extends('layouts.student-dashboard')

@section('title', 'Internship Detail')

@section('styles')
<style>
.card-modern {
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    background: #fff;
    margin-bottom: 2rem;
}
.card-modern .bi {
    color: #6366F1;
    margin-right: 0.5rem;
    font-size: 1.2rem;
    vertical-align: -0.2em;
}
.detail-label {
    font-weight: bold;
    color: #222;
}
.detail-row {
    margin-bottom: 0.7rem;
    font-size: 1.08rem;
    display: flex;
    align-items: center;
}
@media (max-width: 768px) {
    .card-modern {
        padding: 1.2rem !important;
    }
}
.btn-indigo {
    background: #6366F1;
    color: #fff !important;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4F46E5;
    color: #fff !important;
}
.btn-indigo .bi {
    color: inherit !important;
}
</style>
@endsection

@section('page-title')
    <h2 class="mb-0">
        <i class="bi-briefcase me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Internship<span style="color:#6366F1;">Detail</span></span>
    </h2>
@endsection

@section('content')
<div class="card card-modern p-4">
    @if(!$internship)
        <div class="alert alert-info">No internship assigned yet.</div>
    @else
        <div class="detail-row"><i class="bi bi-info-circle"></i> <span class="detail-label">Status:</span>&nbsp; {{ ucfirst($internship->status) }}</div>
        <div class="detail-row"><i class="bi bi-building"></i> <span class="detail-label">Company:</span>&nbsp; {{ $internship->company_name }}</div>
        <div class="detail-row"><i class="bi bi-person-badge"></i> <span class="detail-label">Industry Supervisor:</span>&nbsp; {{ $internship->industrySupervisor->user->name ?? '-' }}</div>
        <div class="detail-row"><i class="bi bi-person-badge-fill"></i> <span class="detail-label">University Supervisor:</span>&nbsp; {{ $internship->universitySupervisor->user->name ?? '-' }}</div>
        <div class="detail-row"><i class="bi bi-calendar-event"></i> <span class="detail-label">Start Date:</span>&nbsp; {{ \Carbon\Carbon::parse($internship->start_date)->format('d M Y') }}</div>
        <div class="detail-row"><i class="bi bi-calendar-check"></i> <span class="detail-label">End Date:</span>&nbsp; {{ \Carbon\Carbon::parse($internship->end_date)->format('d M Y') }}</div>
        <div class="detail-row"><i class="bi bi-list-task"></i> <span class="detail-label">Total Tasks:</span>&nbsp; {{ $internship->tasks->count() }}</div>
        <div class="detail-row"><i class="bi bi-journal-text"></i> <span class="detail-label">Total Daily Reports:</span>&nbsp; {{ $internship->dailyReports->count() }}</div>
        <div class="detail-row">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="detail-label">Offer Letter:</span>&nbsp;
            @if($internship->offer_letter)
                <a href="{{ asset('storage/' . $internship->offer_letter) }}" target="_blank" class="btn btn-indigo btn-sm">
                    <i class="bi bi-eye"></i> View PDF
                </a>
            @else
                <span class="text-muted">Not uploaded</span>
            @endif
        </div>
        <div class="text-end mt-4">
            @if($internship)
                <a href="{{ route('internship.edit') }}" class="btn btn-indigo">
                    <i class="bi bi-pencil"></i> Edit Internship Details
                </a>
            @endif
        </div>
    @endif
</div>
@endsection