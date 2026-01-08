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
                                <input type="text" name="staff_id" class="form-control" value="{{ $supervisor->staff_id }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ $supervisor->department }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $supervisor->phone }}">
                            </div>
                            @php
    $questions = [
        "What is your mother's maiden name?",
        "What was your first pet's name?",
        "What is your favorite food?",
        "What city were you born in?",
        "What is the name of your first school?"
    ];
@endphp

<div class="mb-3">
    <label class="form-label">Security Question 1</label>
    <select name="security_question_1" class="form-select security-question" id="security_question_1">
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}" {{ (old('security_question_1', $user->security_question_1) == $q) ? 'selected' : '' }}>{{ $q }}</option>
        @endforeach
    </select>
    <input name="security_answer_1" class="form-control mt-2" placeholder="Answer" type="text">
</div>
<div class="mb-3">
    <label class="form-label">Security Question 2</label>
    <select name="security_question_2" class="form-select security-question" id="security_question_2">
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}" {{ (old('security_question_2', $user->security_question_2) == $q) ? 'selected' : '' }}>{{ $q }}</option>
        @endforeach
    </select>
    <input name="security_answer_2" class="form-control mt-2" placeholder="Answer" type="text">
</div>
<div class="mb-3">
    <label class="form-label">Security Question 3</label>
    <select name="security_question_3" class="form-select security-question" id="security_question_3">
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}" {{ (old('security_question_3', $user->security_question_3) == $q) ? 'selected' : '' }}>{{ $q }}</option>
        @endforeach
    </select>
    <input name="security_answer_3" class="form-control mt-2" placeholder="Answer" type="text">
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
                    <div class="mb-3 position-relative">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                        <div id="password-modal" style="
                            display:none;
                            position:absolute;
                            left:105%;
                            top:0;
                            width:270px;
                            background:#fff;
                            border:1.5px solid #6366F1;
                            border-radius:1rem;
                            box-shadow:0 2px 16px rgba(99,102,241,0.12);
                            padding:16px 18px;
                            z-index:10;
                            font-size:1rem;
                        ">
                            <div id="pw-length" style="color:#dc3545;"><i class="bi bi-x-circle"></i> At least 8 characters</div>
                            <div id="pw-upper" style="color:#dc3545;"><i class="bi bi-x-circle"></i> At least one uppercase letter</div>
                            <div id="pw-lower" style="color:#dc3545;"><i class="bi bi-x-circle"></i> At least one lowercase letter</div>
                            <div id="pw-number" style="color:#dc3545;"><i class="bi bi-x-circle"></i> At least one number</div>
                            <div id="pw-special" style="color:#dc3545;"><i class="bi bi-x-circle"></i> At least one special character (@$!%*#?&)</div>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required autocomplete="new-password">
                            <div id="pw-match" style="display:none; position:absolute; left:105%; top:0; background:#fff; border:1.5px solid #6366F1; border-radius:1rem; box-shadow:0 2px 16px rgba(99,102,241,0.12); padding:12px 18px; z-index:10; font-size:1rem;">
                            <span id="pw-match-text" style="color:#dc3545;"><i class="bi bi-x-circle"></i> Passwords do not match</span>
                        </div>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Password modal logic
    const pw = document.getElementById('password');
    const modal = document.getElementById('password-modal');
    const pwLength = document.getElementById('pw-length');
    const pwUpper = document.getElementById('pw-upper');
    const pwLower = document.getElementById('pw-lower');
    const pwNumber = document.getElementById('pw-number');
    const pwSpecial = document.getElementById('pw-special');

    pw.addEventListener('focus', function() {
        modal.style.display = 'block';
    });
    pw.addEventListener('blur', function() {
        setTimeout(() => { modal.style.display = 'none'; }, 200); // allow click on modal
    });
    pw.addEventListener('input', function() {
        const val = pw.value;
        // Length
        if (val.length >= 8) {
            pwLength.style.color = '#198754'; pwLength.innerHTML = '<i class="bi bi-check-circle"></i> At least 8 characters';
        } else {
            pwLength.style.color = '#dc3545'; pwLength.innerHTML = '<i class="bi bi-x-circle"></i> At least 8 characters';
        }
        // Uppercase
        if (/[A-Z]/.test(val)) {
            pwUpper.style.color = '#198754'; pwUpper.innerHTML = '<i class="bi bi-check-circle"></i> At least one uppercase letter';
        } else {
            pwUpper.style.color = '#dc3545'; pwUpper.innerHTML = '<i class="bi bi-x-circle"></i> At least one uppercase letter';
        }
        // Lowercase
        if (/[a-z]/.test(val)) {
            pwLower.style.color = '#198754'; pwLower.innerHTML = '<i class="bi bi-check-circle"></i> At least one lowercase letter';
        } else {
            pwLower.style.color = '#dc3545'; pwLower.innerHTML = '<i class="bi bi-x-circle"></i> At least one lowercase letter';
        }
        // Number
        if (/[0-9]/.test(val)) {
            pwNumber.style.color = '#198754'; pwNumber.innerHTML = '<i class="bi bi-check-circle"></i> At least one number';
        } else {
            pwNumber.style.color = '#dc3545'; pwNumber.innerHTML = '<i class="bi bi-x-circle"></i> At least one number';
        }
        // Special
        if (/[@$!%*#?&]/.test(val)) {
            pwSpecial.style.color = '#198754'; pwSpecial.innerHTML = '<i class="bi bi-check-circle"></i> At least one special character (@$!%*#?&)';
        } else {
            pwSpecial.style.color = '#dc3545'; pwSpecial.innerHTML = '<i class="bi bi-x-circle"></i> At least one special character (@$!%*#?&)';
        }
    });

    // Password match logic
    const pwc = document.getElementById('password_confirmation');
    const matchModal = document.getElementById('pw-match');
    const matchText = document.getElementById('pw-match-text');
    function checkMatch() {
        if (pwc.value.length > 0) {
            matchModal.style.display = 'block';
            if (pw.value === pwc.value) {
                matchText.style.color = '#198754';
                matchText.innerHTML = '<i class="bi bi-check-circle"></i> Passwords match';
            } else {
                matchText.style.color = '#dc3545';
                matchText.innerHTML = '<i class="bi bi-x-circle"></i> Passwords do not match';
            }
        } else {
            matchModal.style.display = 'none';
        }
    }
    pwc.addEventListener('focus', checkMatch);
    pwc.addEventListener('input', checkMatch);
    pw.addEventListener('input', checkMatch);
    pwc.addEventListener('blur', function() {
        setTimeout(() => { matchModal.style.display = 'none'; }, 200);
    });
});
</script>
@endsection