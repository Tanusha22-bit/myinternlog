@extends('layouts.university-dashboard')
@section('title', 'My Profile')

@section('styles')
<style>
    .profile-card {
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        background: #fff;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
    }
    .profile-pic-wrapper {
        position: relative;
        width: 140px;
        margin: 0 auto 1.5rem auto;
    }
    .profile-pic {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #6366F1;
        background: #EEF2FF;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    }
    .profile-pic-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #6366F1;
        color: #fff;
        border-radius: 50%;
        padding: 0.5rem;
        cursor: pointer;
        border: 2px solid #fff;
        font-size: 1.2rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.15);
    }
    .profile-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #6366F1;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #334155;
    }
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.7rem 2rem;
        border: none;
        transition: background 0.2s;
    }
    .btn-indigo:hover { background: #4F46E5; }
    .btn-warning {
        background: #FACC15;
        color: #92400E !important;
        border-radius: 999px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.7rem 2rem;
        border: none;
        transition: background 0.2s;
    }
    .btn-warning:hover { background: #eab308; color: #fff !important; }
    .divider {
        border-bottom: 1px solid #e5e7eb;
        margin: 2rem 0;
    }
    .profile-stats .stat {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0"><i class="bi bi-person-circle"></i> My <span class="brand-highlight">Profile</span></h2>
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

<div class="row g-4">
    <!-- Profile Edit Card (pic + details + password) -->
    <div class="col-md-8">
        <div class="profile-card">
            <div class="profile-section-title"><i class="bi bi-pencil-square"></i> Edit Profile</div>
            <form method="POST" action="{{ route('supervisor.university.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="profile-pic-wrapper">
                            <img src="{{ $user->profile_pic ? asset('storage/'.$user->profile_pic) : asset('images/default-avatar.png') }}" class="profile-pic" alt="Profile Picture">
                            <label class="profile-pic-upload" for="profilePicInput" title="Change Picture">
                                <i class="bi bi-camera"></i>
                                <input type="file" name="profile_pic" id="profilePicInput" class="d-none" accept="image/*" onchange="this.form.submit();">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Staff ID</label>
                                <input type="text" class="form-control" value="{{ $supervisor->staff_id }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ $supervisor->department }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $supervisor->phone }}">
                            </div>
                        </div>
                        <button class="btn btn-indigo mt-2 w-100">Update Profile</button>
                    </div>
                </div>
            </form>
            <div class="divider"></div>
            <div class="profile-section-title"><i class="bi bi-key"></i> Change Password</div>
            <form method="POST" action="{{ route('supervisor.university.profile.password') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-warning mt-2 w-100">Update Password</button>
            </form>
        </div>
    </div>
    <!-- Analytics Card -->
    <div class="col-md-4">
        <div class="profile-card text-center">
            <div class="profile-section-title"><i class="bi bi-bar-chart"></i> Activity Overview</div>
            <div class="profile-stats">
                <div class="stat"><i class="bi bi-people"></i> <strong>Students Supervised:</strong> {{ $studentsCount }}</div>
                <div class="stat"><i class="bi bi-chat-dots"></i> <strong>Feedback Given:</strong> {{ $feedbackGiven }}</div>
                <div class="stat"><i class="bi bi-briefcase"></i> <strong>Active Internships:</strong> {{ $activeInternships }}</div>
                <div class="stat"><i class="bi bi-check-circle"></i> <strong>Completed Internships:</strong> {{ $completedInternships }}</div>
            </div>
        </div>
    </div>
</div>
@endsection