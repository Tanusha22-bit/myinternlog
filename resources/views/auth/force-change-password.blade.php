<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password & Security Questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h4 class="mb-4 text-center" style="color:var(--primary);">Set New Password & Security Questions</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('force.change.password.post') }}">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label>New Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <!-- Password modal can be added here if you want live feedback -->
                    </div>
                    <div class="mb-3 position-relative">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <!-- Password match modal can be added here if you want live feedback -->
                    </div>
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="mb-3">
                            <label>Security Question {{ $i }}</label>
                            <select name="security_question_{{ $i }}" class="form-select" required>
                                <option value="">Select a question</option>
                                @foreach($questions as $q)
                                    <option value="{{ $q }}">{{ $q }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="security_answer_{{ $i }}" class="form-control mt-2" placeholder="Answer" required>
                        </div>
                    @endfor
                    <button class="btn btn-indigo w-100 mt-3">Save</button>
                </form>
            </div>
        </div>
        <div class="col-lg-5 forgot-right">
            <div class="forgot-right-overlay"></div>
            <div class="forgot-right-content">
                <h2>Security Setup Required</h2>
                <p>For your security, please set a new password and security questions before accessing your account.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>