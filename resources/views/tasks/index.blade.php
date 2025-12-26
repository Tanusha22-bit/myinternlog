@extends('layouts.student-dashboard')

@section('title', 'Task List')

@section('styles')
<style>
.filter-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.08);
    padding: 1.2rem 2.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: #222;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none !important;
    text-align: center;
    min-width: 200px;
    min-height: 70px;
    margin: 0 8px 16px 8px;
}
.filter-card.all:hover, .filter-card.all.active {
    background: #6366F1;
    color: #fff;
    border-color: #6366F1;
}
.filter-card.pending:hover, .filter-card.pending.active {
    background: #FBBF24;
    color: #222;
    border-color: #FBBF24;
}
.filter-card.in_progress:hover, .filter-card.in_progress.active {
    background: #38BDF8;
    color: #fff;
    border-color: #38BDF8;
}
.filter-card.completed:hover, .filter-card.completed.active {
    background: #22C55E;
    color: #fff;
    border-color: #22C55E;
}
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
.btn-view {
    background: #6366F1;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.btn-view:hover {
    background: #4F46E5;
    color: #fff;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Task<span class="brand-highlight">List</span></h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>

<div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
    <a href="{{ route('tasks.index', ['status' => 'all']) }}"
       class="filter-card all{{ empty($activeStatus) || $activeStatus === 'all' ? ' active' : '' }}">
        <i class="bi bi-collection"></i> All
    </a>
    <a href="{{ route('tasks.index', ['status' => 'pending']) }}"
       class="filter-card pending{{ $activeStatus === 'pending' ? ' active' : '' }}">
        <i class="bi bi-hourglass-split"></i> Pending
    </a>
    <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}"
       class="filter-card in_progress{{ $activeStatus === 'in_progress' ? ' active' : '' }}">
        <i class="bi bi-arrow-repeat"></i> In Progress
    </a>
    <a href="{{ route('tasks.index', ['status' => 'completed']) }}"
       class="filter-card completed{{ $activeStatus === 'completed' ? ' active' : '' }}">
        <i class="bi bi-check-circle"></i> Completed
    </a>
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
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn-view" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection