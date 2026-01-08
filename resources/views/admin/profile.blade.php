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
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
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
    <input name="security_answer_1" class="form-control mt-2" placeholder="Answer" type="text" >
</div>
<div class="mb-3">
    <label class="form-label">Security Question 2</label>
    <select name="security_question_2" class="form-select security-question" id="security_question_2">
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}" {{ (old('security_question_2', $user->security_question_2) == $q) ? 'selected' : '' }}>{{ $q }}</option>
        @endforeach
    </select>
    <input name="security_answer_2" class="form-control mt-2" placeholder="Answer" type="text" >
</div>
<div class="mb-3">
    <label class="form-label">Security Question 3</label>
    <select name="security_question_3" class="form-select security-question" id="security_question_3" >
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}" {{ (old('security_question_3', $user->security_question_3) == $q) ? 'selected' : '' }}>{{ $q }}</option>
        @endforeach
    </select>
    <input name="security_answer_3" class="form-control mt-2" placeholder="Answer" type="text">
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
                <button class="btn btn-warning w-100" type="submit" style="color:#000000;">Update Password</button>
            </form>
        </div>
    </div>
</div>

</script>
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