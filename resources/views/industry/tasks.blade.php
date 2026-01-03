@extends('layouts.industry-dashboard')
@section('title', 'Task Status')
@section('page_icon', 'bi bi-list-task')

@section('styles')
<style>
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.5rem 1.5rem;
        border: none;
        transition: background 0.2s;
    }
    .btn-indigo:hover { background: #4F46E5; }
    .btn-purple {
        background: #6366F1;
        color: #fff !important;
        border-radius: 0.375rem;
        font-weight: 600;
        border: none;
        transition: background 0.2s;
    }
    .btn-purple:hover { background: #4F46E5; }
</style>
@endsection
@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    @php
        $statuses = [
            'all' => ['label' => 'All', 'color' => 'secondary', 'icon' => 'bi-list-task'],
            'completed' => ['label' => 'Completed', 'color' => 'success', 'icon' => 'bi-check-circle'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'primary', 'icon' => 'bi-hourglass-split'],
            'pending' => ['label' => 'Pending', 'color' => 'warning text-dark', 'icon' => 'bi-clock'],
        ];
    @endphp
    @foreach($statuses as $key => $info)
    <div class="col-md-3">
        <a href="{{ route('industry.tasks', ['status' => $key]) }}" style="text-decoration:none;">
            <div class="card-modern p-3 text-center {{ $status == $key ? 'border border-'.$info['color'].' border-3' : '' }}">
                <div class="fw-bold mb-1 text-{{ $info['color'] }}">
                    <i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}
                </div>
                <div class="display-6 text-{{ $info['color'] }}">{{ $counts[$key] ?? 0 }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="d-flex align-items-center justify-content-between gap-3 mb-3 flex-wrap">
    <button class="btn btn-indigo" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="bi bi-plus-circle"></i> Add Task
    </button>
    <form method="get" class="d-flex align-items-center gap-2 mb-0">
        <input type="text" name="search" class="form-control" style="max-width:300px;" placeholder="Search by title or description..." value="{{ request('search') }}">
        <button class="btn btn-indigo" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>
<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content card-modern">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel"><i class="bi bi-plus-circle"></i> Assign New Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('industry.tasks.store') }}">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Task Title" required>
            </div>
            <div class="mb-3">
                <input type="date" name="due_date" class="form-control" placeholder="Due Date">
            </div>
            <div class="mb-3">
                <input type="text" name="description" class="form-control" placeholder="Description">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-indigo" type="submit">Assign Task</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Task List -->
<div class="card-modern p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-list-task"></i> Task List</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Student Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->due_date }}</td>
                    <td>
                        <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'primary' : 'warning text-dark') }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td>{{ $task->student_note }}</td>
                    <td>
    @if($task->status !== 'completed')
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
        <i class="bi bi-pencil"></i>
    </button>
    @else
    <button class="btn btn-sm btn-secondary" disabled><i class="bi bi-pencil"></i></button>
    @endif
    <form action="{{ route('industry.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')"><i class="bi bi-trash"></i></button>
    </form>
    <!-- View Button -->
    <button class="btn btn-sm  btn-purple" data-bs-toggle="modal" data-bs-target="#viewTaskModal{{ $task->id }}">
        <i class="bi bi-eye"></i>
    </button>
</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted">No tasks assigned.</td></tr>
                @endforelse
            </tbody>
        </table>

        @foreach($tasks as $task)
         <!-- View Modal !-->
<div class="modal fade" id="viewTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="viewTaskModalLabel{{ $task->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content card-modern">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTaskModalLabel{{ $task->id }}"><i class="bi bi-eye"></i> Task Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Title:</strong> {{ $task->title }}</p>
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
        <p><strong>Status:</strong>
            <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'primary' : 'warning text-dark') }}">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>
        </p>
        <p><strong>Student Note:</strong> {{ $task->student_note }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content card-modern">
      <div class="modal-header">
        <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}"><i class="bi bi-pencil"></i> Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('industry.tasks.update', $task->id) }}">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
            </div>
            <div class="mb-3">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ $task->due_date }}">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="{{ $task->description }}">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-indigo" type="submit">Update Task</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
    </div>
</div>
@endsection