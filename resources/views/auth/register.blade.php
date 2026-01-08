<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MyInternLog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-main: #F3F4F6;
            --bg-card: #fff;
            --primary: #6366F1;
            --primary-hover: #4F46E5;
            --accent: #22D3EE;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
            --muted: #9CA3AF;
        }
        body {
            background: var(--bg-main);
        }
        .register-row {
            min-height: 100vh;
        }
        .register-left {
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
        }
        .register-form-box {
            width: 100%;
            max-width: 400px;
            padding: 32px 24px;
            border-radius: 1.5rem;
            background: #fff;
            box-shadow: 0 2px 16px rgba(99,102,241,0.08);
        }
        .form-control, .form-select {
            border-radius: 999px;
            padding: 0.75rem 1.25rem;
            font-size: 1.1rem;
            border: 1.5px solid var(--text-secondary);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
        }
        .btn-indigo {
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            font-size: 1.2rem;
            padding: 0.7rem 0;
            border: none;
            box-shadow: 0 2px 8px rgba(99,102,241,0.10);
            transition: background 0.2s;
        }
        .btn-indigo:hover {
            background: var(--primary-hover);
            color: #fff;
        }
        .register-right {
            position: relative;
            background: url('{{ asset('images/login-bg.png') }}') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-right-overlay {
            position: absolute;
            inset: 0;
            background: rgba(49, 56, 90, 0.80);
            backdrop-filter: blur(2px);
            z-index: 1;
        }
        .register-right-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            max-width: 400px;
            margin: auto;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        .register-right-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .register-right-content p {
            font-size: 1.15rem;
            color: #e0e7ef;
        }
        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 1rem;
        }
        .login-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 991.98px) {
            .register-right { display: none; }
            .register-left { flex: 1 0 100%; }
        }
    </style>
</head>
<body>
<a href="{{ url('/') }}" 
   style="position:fixed; top:24px; left:24px; z-index:1000; background:#6366F1; color:#fff; border-radius:50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(99,102,241,0.10); text-decoration:none; font-size:1.5rem;"
   title="Back to Home">
    <i class="bi bi-house-door-fill"></i>
</a>
<div class="container-fluid px-0">
    <div class="row register-row g-0">
        <!-- Left: Form (70%) -->
        <div class="col-lg-7 register-left">
            <div class="register-form-box mx-auto">
                <h4 class="mb-4 text-center" style="color:var(--primary);">Registration Form</h4>
                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ url('/register') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
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
    <label>Security Question 1</label>
    <select name="security_question_1" class="form-select security-question" id="security_question_1" required>
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}">{{ $q }}</option>
        @endforeach
    </select>
    <input type="text" name="security_answer_1" class="form-control mt-2" placeholder="Answer" required>
</div>
<div class="mb-3">
    <label>Security Question 2</label>
    <select name="security_question_2" class="form-select security-question" id="security_question_2" required>
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}">{{ $q }}</option>
        @endforeach
    </select>
    <input type="text" name="security_answer_2" class="form-control mt-2" placeholder="Answer" required>
</div>
<div class="mb-3">
    <label>Security Question 3</label>
    <select name="security_question_3" class="form-select security-question" id="security_question_3" required>
        <option value="">Select a question</option>
        @foreach($questions as $q)
            <option value="{{ $q }}">{{ $q }}</option>
        @endforeach
    </select>
    <input type="text" name="security_answer_3" class="form-control mt-2" placeholder="Answer" required>
</div>
                    <div class="mb-3 position-relative">
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required autocomplete="new-password">
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
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" required autocomplete="new-password">
    <div id="pw-match" style="display:none; position:absolute; left:105%; top:0; background:#fff; border:1.5px solid #6366F1; border-radius:1rem; box-shadow:0 2px 16px rgba(99,102,241,0.12); padding:12px 18px; z-index:10; font-size:1rem;">
        <span id="pw-match-text" style="color:#dc3545;"><i class="bi bi-x-circle"></i> Passwords do not match</span>
    </div>
</div>
                    <div class="mb-3">
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="industry_sv">Industry Supervisor</option>
                            <option value="university_sv">University Supervisor</option>
                        </select>
                    </div>
                    <button class="btn btn-indigo w-100">Register</button>
                </form>
                <div class="mt-3 text-center">
                    Already have an account? <a href="{{ route('login') }}" class="login-link">Login</a>
                </div>
            </div>
        </div>
        <!-- Right: Image & Overlay (30%) -->
        <div class="col-lg-5 register-right">
            <div class="register-right-overlay"></div>
            <div class="register-right-content">
                <h2>Welcome Intern!</h2>
                <p>Register to start your digital internship logbook.<br>Submit daily reports and connect with your supervisor easily.</p>
            </div>
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

    if (pw) {
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
    }

    // Password match logic
    const pwc = document.getElementById('password_confirmation');
    const matchModal = document.getElementById('pw-match');
    const matchText = document.getElementById('pw-match-text');
    function checkMatch() {
        if (pwc && pw && pwc.value.length > 0) {
            matchModal.style.display = 'block';
            if (pw.value === pwc.value) {
                matchText.style.color = '#198754';
                matchText.innerHTML = '<i class="bi bi-check-circle"></i> Passwords match';
            } else {
                matchText.style.color = '#dc3545';
                matchText.innerHTML = '<i class="bi bi-x-circle"></i> Passwords do not match';
            }
        } else if (matchModal) {
            matchModal.style.display = 'none';
        }
    }
    if (pwc) {
        pwc.addEventListener('focus', checkMatch);
        pwc.addEventListener('input', checkMatch);
        if (pw) pw.addEventListener('input', checkMatch);
        pwc.addEventListener('blur', function() {
            setTimeout(() => { matchModal.style.display = 'none'; }, 200);
        });
    }
});
</script>
</body>
</html>