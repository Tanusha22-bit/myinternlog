@extends('layouts.admin-dashboard')

@section('title', 'Profile')
@section('page_icon', 'bi bi-person-circle')

@push('styles')
<style>
.avatar-xl {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f5f7ff;
    border: 3px solid #6366F1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem auto;
    overflow: hidden;
}
.avatar-xl img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.profile-label {
    font-weight: 600;
    color: #6366F1;
}
.form-label {
    color: #222 !important;
    font-weight: 500;
}
.btn-indigo {
    background: #6366F1;
    color: #fff !important;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4f46e5;
    color: #fff !important;
}
</style>
@endpush

@section('content')
<div class="row">
    <!-- Edit Profile Card (Left) -->
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4 h-100">
            <h4 class="mb-3" ><i  style="color:#6366F1;" class="bi bi-person-circle"></i> Edit Profile</h4>
            <div class="avatar-xl">
                @if($user->profile_pic)
                    <img src="{{ asset('storage/'.$user->profile_pic) }}" alt="Avatar">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar">
                @endif
            </div>
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control" accept="image/*">
                </div>
                <button class="btn btn-indigo w-100" type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Change Password Card (Right) -->
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4 h-100">
            <h4 class="mb-3"><i style="color:#6366F1;"class="bi bi-key"></i> Change Password</h4>
            <form method="POST" action="{{ route('admin.profile.changePassword') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-warning w-100" type="submit" style="color:#000000;">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection