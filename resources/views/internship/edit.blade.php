@extends('layouts.student-dashboard')

@section('title', 'Edit Internship Detail')

@section('styles')
<style>
.card-modern { border-radius: 18px; box-shadow: 0 4px 24px rgba(99,102,241,0.10); background: #fff; }
.btn-indigo {
    background: #6366F1;
    color: #fff;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus { background: #4F46E5; color: #fff; }
.form-label { font-weight: bold; }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Edit<span class="brand-highlight">Internship</span></h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
@endif
<div class="card card-modern p-4">
    <form method="POST" action="{{ route('internship.update', $internship->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $internship->company_name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Company Address</label>
            <input type="text" name="company_address" class="form-control" value="{{ old('company_address', $internship->company_address) }}" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $internship->start_date) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $internship->end_date) }}" required>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Industry Supervisor</label>
            <input type="text" class="form-control" value="{{ $internship->industrySupervisor->user->name ?? '-' }}" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">University Supervisor</label>
            <input type="text" class="form-control" value="{{ $internship->universitySupervisor->user->name ?? '-' }}" readonly>
        </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Internship Offer Letter (PDF, max 10MB)</label>
            @if($internship->offer_letter)
            <div class="mb-2">
                <a href="{{ asset('storage/' . $internship->offer_letter) }}" target="_blank" class="btn btn-indigo btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> View Current File
                </a>
                <span class="text-danger ms-2">(Uploading a new file will replace the current one)</span>
            </div>
        @endif
        <input type="file" name="offer_letter" accept="application/pdf" class="form-control">
        <div class="form-text text-danger" style="font-weight:500;">
            Only PDF allowed. Max size: 10MB.
        </div>
        </div>
        <button class="btn btn-indigo w-100 mt-3">Update Internship</button>
        <a href="{{ route('internship.show', $internship->id) }}" class="btn btn-danger w-100 mt-2">Cancel</a>
    </form>
</div>
@endsection