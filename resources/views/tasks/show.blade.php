@extends('layouts.student-dashboard')

@section('title', 'Task Detail')

@section('styles')
<style>
.btn-success-custom {
    background: #22C55E;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-success-custom:hover, .btn-success-custom:focus {
    background: #16a34a;
    color: #fff;
}
.btn-indigo {
    background: #6366F1;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus {
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
<div class="card card-modern p-4 mb-3">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <strong>Task Description:</strong>
        <div class="border rounded p-2 bg-white mb-2">{{ $task->description }}</div>
    </div>
    <div class="mb-3">
        <strong>Due Date:</strong>
        <div class="border rounded p-2 bg-white mb-2">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</div>
    </div>
    <form method="POST" action="{{ route('tasks.update', $task->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label"><strong>Status:</strong></label>
        <select name="status" class="form-select" required>
            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label"><strong>Student Note:</strong></label>
        <textarea name="student_note" class="form-control" rows="3">{{ $task->student_note }}</textarea>
    </div>
    <button class="btn btn-success-custom" type="submit">Update</button>
    <a href="{{ route('tasks.index') }}" class="btn btn-indigo ms-2">Back</a>
</form>
</div>
@endsection