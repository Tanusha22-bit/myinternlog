@extends('layouts.admin-dashboard')

@section('title', 'Profile')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">My<span class="brand-highlight">Profile</span></h2>
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
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-person"></i> Personal Details</h5>
            <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                 <button class="btn w-100" style="background: #FFA500; color: #fff;">Update Profile</button>
            </form>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-key"></i> Change Password</h5>
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
                <button class="btn btn-orange w-100">Change Password</button>
            </form>
        </div>
    </div>
</div>
@endsection