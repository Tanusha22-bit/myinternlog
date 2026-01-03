<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Industry Supervisor Dashboard') - MyInternLog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --bg-main: #F3F4F6;
            --bg-card: #fff;
            --sidebar: #0F172A;
            --sidebar-hover: #4F46E5;
            --primary: #6366F1;
            --accent: #FBBF24;
            --text-primary: #0F172A;
        }
        body { background: var(--bg-main); }
        .brand-highlight { color: #6366F1; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            background: var(--sidebar);
            color: #fff;
            padding: 24px 0 24px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 20px 20px 0;
            box-shadow: 2px 0 16px rgba(99,102,241,0.04);
            width: 260px;
            min-width: 260px;
        }
        .sidebar .logo-img { height: 44px; margin-bottom: 18px; }
        .sidebar .welcome {
            font-weight: 600;
            margin-bottom: 32px;
            font-size: 1.1rem;
            width: 100%;
            text-align: center;
        }
        .sidebar .nav-link {
            color: #fff;
            font-size: 1.08rem;
            margin: 10px 0;
            padding: 10px 24px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar .nav-link.active {
            background: rgba(251,191,36,0.18);
            color: var(--accent);
            border-left: 5px solid var(--accent);
            font-weight: 600;
        }
        .sidebar .nav-link:hover:not(.active) {
            background: rgba(251,191,36,0.10);
            color: var(--accent);
        }
        .logout-btn {
            background: transparent;
            color: #fff;
            font-size: 1.08rem;
            padding: 10px 24px;
            border-radius: 999px;
            width: 100%;
            margin-top: auto;
            margin-bottom: 16px;
            font-weight: 600;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .logout-btn:hover {
            background: #ef4444;
            color: #fff;
        }
        .dashboard-content {
            padding: 40px 32px;
            margin-left: 260px;
        }
        .brand-highlight { color: var(--sidebar); }
        .card-modern {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(99,102,241,0.08);
            background: var(--bg-card);
        }
        @media (max-width: 991.98px) {
            .sidebar { border-radius: 0; min-height: auto; flex-direction: row; padding: 16px; width: 100px; min-width: 100px; }
            .dashboard-content { padding: 16px; margin-left: 100px; }
        }
        @media (max-width: 767.98px) {
            .sidebar { flex-direction: row; align-items: flex-start; padding: 8px; width: 70px; min-width: 70px; }
            .dashboard-content { padding: 8px; margin-left: 70px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <img src="{{ asset('images/myinternlog-logo.png') }}" alt="Logo" class="logo-img">
        <div class="welcome mb-4 text-center w-100" style="font-weight:600;">
            Welcome Name!
        </div>
        <nav class="flex-grow-1 w-100">
            <a href="{{ url('/dashboard/industry') }}" class="nav-link{{ request()->is('dashboard/industry') ? ' active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Home
            </a>
            <a href="{{ route('industry.students') }}" class="nav-link{{ request()->routeIs('industry.students') ? ' active' : '' }}">
                <i class="bi bi-people"></i> My Students
            </a>
            <a href="{{ route('industry.tasks') }}" class="nav-link{{ request()->routeIs('industry.tasks') ? ' active' : '' }}">
                <i class="bi bi-list-task"></i> Task Status
            </a>
            <a href="{{ route('industry.reports') }}" class="nav-link{{ request()->routeIs('industry.reports') ? ' active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Report
            </a>
            <a href="{{ route('industry.profile') }}" class="nav-link{{ request()->routeIs('industry.profile') ? ' active' : '' }}">
                <i class="bi bi-person"></i> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </div>
    <div class="dashboard-content">
        <!-- Header Row: Page Title (left) & User Info (right) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 d-flex align-items-center" style="font-weight:700;">
                @php
                    $title = trim($__env->yieldContent('title', 'Dashboard'));
                    $icon = trim($__env->yieldContent('page_icon', 'bi bi-house-door-fill'));
                    $words = explode(' ', $title, 2);
                @endphp
                <i class="{{ $icon }} me-2" style="color:#6366F1; font-size:1.5em;"></i>
                <span style="color:#111;">
                    {{ $words[0] ?? '' }}
                </span>
                @if(isset($words[1]))
                    <span style="color:#6366F1;"> {{ $words[1] }}</span>
                @endif
            </h2>
            <div class="d-flex align-items-center" style="gap: 12px;">
                <span style="font-weight:600;">
                    {{ Auth::user()->name }}
                </span>
                <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : asset('images/default-avatar.png') }}"
                     alt="Profile Picture"
                     style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid #6366F1;">
            </div>
        </div>
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>