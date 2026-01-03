@extends('layouts.industry-dashboard')
@section('title', 'Profile')
@section('page_icon', 'bi bi-person')

@section('styles')
<style>
    .profile-pic {
        width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 4px solid #6366F1;
    }
    .btn-indigo { background: #6366F1; color: #fff !important; border-radius: 999px; font-weight: 600; }
    .btn-indigo:hover { background: #4F46E5; }
    .card-modern { border-radius: 22px; box-shadow: 0 4px 24px rgba(99,102,241,0.10); background: #fff; padding: 2rem; border: none; }
    .form-label { font-weight: bold; }
    @media (max-width: 991.98px) {
        .profile-row { flex-direction: column !important; }
        .profile-col { width: 100% !important; }
    }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="d-flex gap-4 profile-row" style="display:flex;">
    <!-- Profile Update Form -->
    <div class="card-modern mb-4 profile-col" style="width:50%;">
        <h5 class="mb-4" style="color:#6366F1;"><i class="bi bi-person-circle"></i> Edit Profile</h5>
        <form method="POST" action="{{ route('industry.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 text-center">
                <img src="{{ $user->profile_pic ? asset('storage/'.$user->profile_pic) : asset('images/default-avatar.png') }}" class="profile-pic mb-2" alt="Profile Picture">
                <div>
                    <input type="file" name="profile_pic" class="form-control" style="max-width:220px; margin:auto;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $industrySupervisor->phone) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" name="company" class="form-control" value="{{ old('company', $industrySupervisor->company) }}">
            </div>
            <button class="btn btn-indigo px-4">Update Profile</button>
        </form>
    </div>
    <!-- Password Change Form -->
    <div class="card-modern mb-4 profile-col" style="width:50%;">
        <h5 class="mb-4" style="color:#6366F1;"><i class="bi bi-key"></i> Change Password</h5>
        <form method="POST" action="{{ route('industry.profile.password') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <button class="btn btn-warning px-4">Update Password</button>
        </form>
    </div>
</div>
@endsection