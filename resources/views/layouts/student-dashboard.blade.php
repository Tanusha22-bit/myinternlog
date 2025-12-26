<!-- filepath: resources/views/layouts/student-dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en" class="{{ session('theme','light') === 'dark' ? 'theme-dark' : 'theme-light' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - MyInternLog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #F3F4F6;
            --bg-card: #fff;
            --sidebar: #0F172A;
            --sidebar-hover: #4F46E5;
            --primary: #6366F1; 
            --primary-hover: #4F46E5;
            --accent: #FBBF24;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
            --muted: #9CA3AF;
        }
        body { background: var(--bg-main); }
        .sidebar {
            background: var(--sidebar);
            color: #fff;
            min-height: 100vh;
            padding: 24px 0 24px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 20px 20px 0; /* Only round right side */
            box-shadow: 2px 0 16px rgba(99,102,241,0.04);
        }
        .sidebar .logo-img { height: 44px; margin-bottom: 18px; }
        .sidebar .welcome { font-weight: 600; margin-bottom: 32px; font-size: 1.1rem; }
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
    background: rgba(251,191,36,0.18); /* lighter amber background */
    color: var(--accent);
    border-left: 5px solid var(--accent);
    font-weight: 600;
}
.sidebar .nav-link:hover:not(.active) {
    background: rgba(251,191,36,0.10);
    color: var(--accent);
}
.sidebar .nav-link:active,
.sidebar .nav-link:focus {
    background: rgba(251,191,36,0.10); /* subtle amber on click/focus */
    color: var(--accent);
}
        .logout-btn {
            background: var(--accent);
            color: var(--text-primary);
            border: none;
            width: 80%;
            margin-top: auto;
            font-size: 1.08rem;
            padding: 10px 0;
            border-radius: 999px;
            margin-bottom: 16px;
            transition: background 0.2s;
        }
        .logout-btn:hover { background: #67e8f9; }
        .dashboard-content {
            padding: 40px 32px;
        }
        .brand-highlight { color: var(--primary); }
        .avatar {
            background: var(--accent);
            color: var(--text-primary);
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: bold;
        }
        .card-modern {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(99,102,241,0.08);
            background: var(--bg-card);
        }
        .quick-link {
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            padding: 16px 0;
            text-align: center;
            font-weight: 500;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
        }
        .quick-link:hover { background: var(--primary-hover); color: #fff; }
        @media (max-width: 991.98px) {
            .sidebar { border-radius: 0; min-height: auto; flex-direction: row; padding: 16px; }
            .dashboard-content { padding: 16px; }
        }
        @media (max-width: 767.98px) {
            .sidebar { flex-direction: row; align-items: flex-start; padding: 8px; }
            .dashboard-content { padding: 8px; }
        }
    </style>
   @yield('styles')
</head>
<body class="{{ session('theme','light') === 'dark' ? 'dark-mode' : '' }}">
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 sidebar">
            <img src="{{ asset('images/myinternlog-logo.png') }}" alt="Logo" class="logo-img">
            <div class="welcome mb-3">Welcome, <br>{{ auth()->user()->name ?? 'Name' }}!</div>
            <nav class="flex-grow-1 w-100">
    <a href="{{ url('/dashboard') }}" class="nav-link{{ request()->is('dashboard') ? ' active' : '' }}">
    <i class="bi bi-house-door-fill"></i> Home
    </a>
    <a href="{{ route('daily-report.create') }}" class="nav-link{{ request()->routeIs('daily-report.create') ? ' active' : '' }}">
        <i class="bi bi-journal-text"></i>Daily Report
    </a>
    <a href="{{ route('daily-report.list') }}" class="nav-link{{ request()->routeIs('daily-report.list') ? ' active' : '' }}">
        <i class="bi bi-list-check"></i>Report List
    </a>
    <a href="{{ route('tasks.index') }}" class="nav-link{{ request()->routeIs('tasks.*') ? ' active' : '' }}">
        <i class="bi bi-check2-square"></i>Task
    </a>
    <a href="{{ route('internship.show', auth()->user()->student->internship->id ?? 1) }}" class="nav-link{{ request()->routeIs('internship.*') ? ' active' : '' }}">
    <i class="bi bi-briefcase"></i>Internship Detail
    </a>
    <a href="{{ route('profile.show') }}" class="nav-link{{ request()->routeIs('profile.*') ? ' active' : '' }}">
        <i class="bi bi-person"></i>Profile
    </a>
     <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent" style="color: #fff;">
        <i class="bi bi-box-arrow-right"></i> Logout
    </button>
</form>
</nav>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9 col-md-8 dashboard-content">
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>