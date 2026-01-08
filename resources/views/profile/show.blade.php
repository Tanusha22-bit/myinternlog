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
@section('page-title')
    <h2 class="mb-0">
        <i class="bi bi-person me-2" style="color:#6366F1; font-size:2rem; vertical-align:-0.2em;"></i>
        <span style="font-weight:500;">My<span style="color:#6366F1;">Profile</span></span>
    </h2>
@endsection

@section('content')
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
                    <label class="form-label">Matric Number</label>
                    <input name="student_id" class="form-control" value="{{ old('student_id', $student->student_id ?? '') }}" required>
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
                <button class="btn btn-orange w-100 mt-3">Update Profile</button>
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
         <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-key"></i> Change Password</h5>
            <form method="POST" action="{{ route('profile.changePassword') }}">
                @csrf
                <div class="mb-3 position-relative">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
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
                <button class="btn btn-orange w-100 mt-3">Update Password</button>
            </form>
        </div>
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
@endsection