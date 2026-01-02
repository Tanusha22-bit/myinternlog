@extends('layouts.industry-dashboard')
@section('title', 'Edit Task')

@section('content')
<div class="card-modern p-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-pencil"></i> Edit Task</h5>
    <form method="POST" action="{{ route('industry.tasks.update', $task->id) }}">
        @csrf
        @method('PUT')
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
        <button class="btn btn-indigo">Update Task</button>
    </form>
</div>
@endsection