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
    --sidebar: #0F172A; /* Match university supervisor */
    --sidebar-hover: #4F46E5;
    --primary: #6366F1;
    --accent: #FBBF24;
    --text-primary: #0F172A;
}
        body { background: var(--bg-main); }
        .brand-highlight { color: #6366F1; }
        .sidebar {
            background: var(--sidebar);
            color: #fff;
            min-height: 100vh;
            padding: 24px 0 24px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 20px 20px 0;
            box-shadow: 2px 0 16px rgba(99,102,241,0.04);
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
        .brand-highlight { color: var(--sidebar); }
        .card-modern {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(99,102,241,0.08);
            background: var(--bg-card);
        }
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
<body>
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 sidebar">
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
                <a href="#" class="nav-link"><i class="bi bi-person"></i> Profile</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
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