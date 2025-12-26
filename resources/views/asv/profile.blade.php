@extends('layouts.university-dashboard')

@section('title', 'Supervisor Profile')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Supervisor <span class="brand-highlight">Profile</span></h2>
    <div class="avatar ms-3">{{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}</div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-modern p-4">
            <form method="POST" action="{{ route('supervisor.university.profile.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $profile->department ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone ?? '') }}">
                </div>
                <button class="btn btn-primary w-100">Save Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection