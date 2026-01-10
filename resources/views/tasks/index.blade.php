@extends('layouts.student-dashboard')

@section('title', 'Task List')

@section('styles')
<style>
.dashboard-card {
    border-radius: 28px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    color: #22223b;
    min-width: 220px;
    min-height: 120px;
    max-width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none !important;
    transition: box-shadow 0.2s, filter 0.2s;
    font-size: 1.1rem;
}
.dashboard-card .display-6 {
    font-size: 2.2rem;
    font-weight: 700;
}
.dashboard-card .fw-bold {
    font-size: 1.1rem;
}
.dashboard-card .stat-icon {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
}
.dashboard-card .stat-label {
    font-size: 1.1rem;
    font-weight: 600;
}
.dashboard-card .stat-count {
    font-size: 2.2rem;
    font-weight: bold;
    margin-top: 0.5rem;
}
.dashboard-card:hover, .dashboard-card.active {
    filter: brightness(0.97);
    box-shadow: 0 4px 24px rgba(99,102,241,0.12);
    text-decoration: none !important;
}
.bg-indigo { background: #6366F1; color: #fff !important; }
.bg-yellow { background: #FBBF24; color: #92400E !important; }
.bg-blue { background: #38BDF8; color: #fff !important; }
.bg-green { background: #22C55E; color: #fff !important; }

.table-modern th {
    background: #0F172A;
    color: #fff;
    border: none;
}
.table-modern td {
    border: none;
    font-size: 1rem;
}
.table-modern tr {
    border-bottom: 1px solid #f3f4f6;
}
.table-modern tr:last-child {
    border-bottom: none;
}
.action-btn {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 4px;
    border: 2px solid transparent;
    background: #fff;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.action-btn.view {
    border-color: #6366F1;
    color: #6366F1;
}
.action-btn.view:hover, .action-btn.view:focus {
    background: #6366F1;
    color: #fff;
}
</style>
@endsection

@section('page-title')
    <h2 class="mb-0">
        <i class="bi-list-task me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">Task<span style="color:#6366F1;">List</span></span>
    </h2>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4 mb-4 justify-content-center">
    <div class="col-md-3 d-flex">
        <a href="{{ route('tasks.index', ['status' => 'all']) }}"
           class="dashboard-card bg-indigo flex-fill text-center{{ empty($activeStatus) || $activeStatus === 'all' ? ' active' : '' }}"
           style="min-width:220px; min-height:120px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-decoration:none;">
            <div class="fw-bold mb-1"><i class="bi bi-collection"></i> All</div>
            <div class="display-6">{{ $counts['all'] ?? $tasks->total() }}</div>
        </a>
    </div>
    <div class="col-md-3 d-flex">
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}"
           class="dashboard-card bg-yellow flex-fill text-center{{ $activeStatus === 'pending' ? ' active' : '' }}"
           style="min-width:220px; min-height:120px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-decoration:none;">
            <div class="fw-bold mb-1"><i class="bi bi-hourglass-split"></i> Pending</div>
            <div class="display-6">{{ $counts['pending'] ?? 0 }}</div>
        </a>
    </div>
    <div class="col-md-3 d-flex">
        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}"
           class="dashboard-card bg-blue flex-fill text-center{{ $activeStatus === 'in_progress' ? ' active' : '' }}"
           style="min-width:220px; min-height:120px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-decoration:none;">
            <div class="fw-bold mb-1"><i class="bi bi-arrow-repeat"></i> In Progress</div>
            <div class="display-6">{{ $counts['in_progress'] ?? 0 }}</div>
        </a>
    </div>
    <div class="col-md-3 d-flex">
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}"
           class="dashboard-card bg-green flex-fill text-center{{ $activeStatus === 'completed' ? ' active' : '' }}"
           style="min-width:220px; min-height:120px; display:flex; flex-direction:column; justify-content:center; align-items:center; text-decoration:none;">
            <div class="fw-bold mb-1"><i class="bi bi-check-circle"></i> Completed</div>
            <div class="display-6">{{ $counts['completed'] ?? 0 }}</div>
        </a>
    </div>
</div>

<div class="card card-modern p-4">
    <h5 class="mb-3"><i class="bi bi-list-task"></i> Your Tasks</h5>
    @if($tasks->isEmpty())
        <div class="alert alert-info">No tasks assigned yet.</div>
    @else
    <div class="table-responsive">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Student Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                    <td>
                        <span class="badge 
                            @if($task->status == 'completed') bg-success
                            @elseif($task->status == 'in_progress') bg-info text-dark
                            @else bg-warning text-dark @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td>
                        {{ $task->student_note ?? '-' }}
                    </td>
                    <td>
                        <a href="{{ route('tasks.show', $task->id) }}" class="action-btn view" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $tasks->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    @endif
</div>
@endsection