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
            <button class="btn btn-indigo px-4">Update Profile</button>
        </form>
    </div>
    <!-- Password Change Form -->
    <div class="card-modern mb-4 profile-col" style="width:50%;">
        <h5 class="mb-4" style="color:#6366F1;"><i class="bi bi-key"></i> Change Password</h5>
        <form method="POST" action="{{ route('industry.profile.password') }}">
            @csrf
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <div class="mb-3 position-relative">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
            </div>
            <div class="mb-3 position-relative">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" id="password" class="form-control" placeholder="Enter new password" required>
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
                        <input type="password" name="new_password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required autocomplete="new-password">
                            <div id="pw-match" style="display:none; position:absolute; left:105%; top:0; background:#fff; border:1.5px solid #6366F1; border-radius:1rem; box-shadow:0 2px 16px rgba(99,102,241,0.12); padding:12px 18px; z-index:10; font-size:1rem;">
                            <span id="pw-match-text" style="color:#dc3545;"><i class="bi bi-x-circle"></i> Passwords do not match</span>
                        </div>
                    </div>
            <button class="btn btn-warning px-4">Update Password</button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.security-question');

    function updateOptions() {
        let selected = Array.from(selects).map(s => s.value).filter(v => v !== "");
        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value === "") return; // Skip placeholder
                option.disabled = selected.includes(option.value) && select.value !== option.value;
            });
        });
    }

    selects.forEach(select => {
        select.addEventListener('change', updateOptions);
    });

    // Run once on load to disable duplicates if editing
    updateOptions();
});
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
</script>
@endsection