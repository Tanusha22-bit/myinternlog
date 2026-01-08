<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - MyInternLog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        .forgot-row {
            min-height: 100vh;
        }
        .forgot-left {
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
        }
        .forgot-form-box {
            width: 100%;
            max-width: 400px;
            padding: 32px 24px;
            border-radius: 1.5rem;
            background: #fff;
            box-shadow: 0 2px 16px rgba(99,102,241,0.08);
        }
        .form-control {
            border-radius: 999px;
            padding: 0.75rem 1.25rem;
            font-size: 1.1rem;
            border: 1.5px solid var(--text-secondary);
        }
        .form-control:focus {
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
        .forgot-right {
            position: relative;
            background: url('{{ asset('images/login-bg.png') }}') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-right-overlay {
            position: absolute;
            inset: 0;
            background: rgba(49, 56, 90, 0.80);
            backdrop-filter: blur(2px);
            z-index: 1;
        }
        .forgot-right-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
            max-width: 400px;
            margin: auto;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        .forgot-right-content h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .forgot-right-content p {
            font-size: 1.15rem;
            color: #e0e7ef;
        }
        .register-link {
            display: inline-block;
            margin-top: 12px;
            color: var(--primary);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
        }
        .register-link:hover {
            text-decoration: underline;
            color: var(--primary-hover);
        }
        @media (max-width: 991.98px) {
            .forgot-right { display: none; }
            .forgot-left { flex: 1 0 100%; }
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
    <div class="row forgot-row g-0">
        <div class="col-lg-7 forgot-left">
            <div class="forgot-form-box mx-auto">
                <h4 class="mb-4 text-center" style="color:var(--primary);">Reset Password</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                 @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                    </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('reset.password') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div class="mb-3 position-relative">
                        <label class="mb-1">New Password</label>
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
                        <label class="mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required autocomplete="new-password">
                            <div id="pw-match" style="display:none; position:absolute; left:105%; top:0; background:#fff; border:1.5px solid #6366F1; border-radius:1rem; box-shadow:0 2px 16px rgba(99,102,241,0.12); padding:12px 18px; z-index:10; font-size:1rem;">
                            <span id="pw-match-text" style="color:#dc3545;"><i class="bi bi-x-circle"></i> Passwords do not match</span>
                        </div>
                    </div>
                    <button class="btn btn-indigo w-100">Reset Password</button>
                </form>
                <div class="mt-2 text-center">
                    <a href="{{ route('forgot.password') }}" class="register-link">Back to Forgot Password</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5 forgot-right">
            <div class="forgot-right-overlay"></div>
            <div class="forgot-right-content">
                <h2>Set a New Password</h2>
                <p>Enter your new password to complete the reset process.</p>
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
</body>
</html>