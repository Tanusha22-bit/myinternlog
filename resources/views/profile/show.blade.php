@extends('layouts.student-dashboard')

@section('title', 'Profile')

@section('styles')
<style>
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
.btn-orange {
    background: #FBBF24;
    color: #222;
    border: none;
    border-radius: 999px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-orange:hover, .btn-orange:focus {
    background: #eab308;
    color: #222;
}
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
.form-label {
    font-weight: bold;
}
.rounded-circle {
    border-radius: 50% !important;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">My<span class="brand-highlight">Profile</span></h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>
@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif
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
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-person"></i> Personal Details</h5>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="text-center mb-3">
                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('images/default-avatar.png') }}"
                        alt="Profile Picture"
                        class="rounded-circle"
                        style="width:120px; height:120px; object-fit:cover; border:3px solid #6366F1;">
                </div>
                <div class="mb-3 text-center">
                    <input type="file" name="profile_pic" accept="image/*" class="form-control" style="display:inline-block; width:auto;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Program</label>
                    <input name="program" class="form-control" value="{{ old('program', $student->program ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <input name="semester" class="form-control" value="{{ old('semester', $student->semester ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input name="phone" class="form-control" value="{{ old('phone', $student->phone ?? '') }}" required>
                </div>
                <button class="btn btn-orange w-100 mt-3">Update Profile</button>
            </form>
            <form method="POST" action="{{ route('profile.changePassword') }}">
                @csrf
                <hr>
                <h5 class="mb-3"><i class="bi bi-key"></i> Change Password</h5>
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <button class="btn btn-orange w-100 mt-3">Update Password</button>
            </form>
        </div>
    </div>

    <!--Card for internship details-->
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4 mb-3">
            <h5 class="mb-3"><i class="bi bi-briefcase"></i> Internship Summary</h5>
            @if($internship)
                <div class="mb-2"><i class="bi bi-info-circle"></i> <strong>Status:</strong> {{ ucfirst($internship->status) }}</div>
                <div class="mb-2"><i class="bi bi-building"></i> <strong>Company:</strong> {{ $internship->company_name }}</div>
                <div class="mb-2"><i class="bi bi-person-badge"></i> <strong>Industry Supervisor:</strong> {{ $internship->industrySupervisor->user->name ?? '-' }}</div>
                <div class="mb-2"><i class="bi bi-calendar-event"></i> <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d M Y') }}</div>
                <div class="mb-2"><i class="bi bi-calendar-check"></i> <strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d M Y') }}</div>
                <a href="{{ route('internship.show', $internship->id) }}" class="btn btn-indigo mt-3 w-100"><i class="bi bi-eye"></i> View Details</a>
            @else
                <div class="text-muted">No internship assigned.</div>
            @endif
        </div>

        <!--Card for activity overview-->
        <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-bar-chart"></i> Activity Overview</h5>
            <div class="mb-2"><i class="bi bi-journal-text"></i> <strong>Total Daily Reports Submitted:</strong> {{ $totalReports }}</div>
            <div class="mb-2"><i class="bi bi-calendar3"></i> <strong>Reports Submitted This Month:</strong> {{ $reportsThisMonth }}</div>
            <div class="mb-2"><i class="bi bi-list-check"></i> <strong>Total Tasks Completed:</strong> {{ $totalTasksCompleted }}</div>
            <div class="mb-2"><i class="bi bi-chat-dots"></i> <strong>Total Feedback Received:</strong> {{ $totalFeedback }}</div>
        </div>
    </div>
</div>
@endsection