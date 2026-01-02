<!DOCTYPE html>
<html lang="en" class="{{ session('theme','light') === 'dark' ? 'theme-dark' : 'theme-light' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard') - MyInternLog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom-theme.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .sidebar {
            background: #009999;
            color: #fff;
            min-height: 100vh;
            padding: 24px 0 24px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 20px 0 0 20px;
            box-shadow: 2px 0 16px rgba(0,0,0,0.04);
        }
        .sidebar .logo-img { height: 48px; margin-bottom: 16px; }
        .sidebar .welcome { font-weight: 600; margin-bottom: 32px; font-size: 1.1rem; }
        .sidebar .nav-link {
            color: #fff;
            font-size: 1.1rem;
            margin: 12px 0;
            padding: 10px 24px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #00b3b3;
            color: #fff;
            text-decoration: none;
        }
        .logout-btn {
            background: #FFA500;
            color: #fff;
            border: none;
            width: 80%;
            margin-top: auto;
            font-size: 1.1rem;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 16px;
            transition: background 0.2s;
        }
        .logout-btn:hover { background: #ffb733; }
        .dashboard-content {
            padding: 40px 32px;
        }
        .brand-highlight { color: #FFA500; }
        .avatar {
            background: #e0f7fa;
            color: #009999;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-modern {
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            background: #e0f7fa;
        }
        .quick-link {
            background: #009999;
            color: #fff;
            border-radius: 10px;
            padding: 18px 0;
            text-align: center;
            font-weight: 500;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
        }
        .quick-link:hover { background: #00b3b3; color: #fff; }
        @media (max-width: 991.98px) {
            .sidebar { border-radius: 0; min-height: auto; flex-direction: row; padding: 16px; }
            .dashboard-content { padding: 16px; }
        }
        @media (max-width: 767.98px) {
            .sidebar { flex-direction: row; align-items: flex-start; padding: 8px; }
            .dashboard-content { padding: 8px; }
        }
    </style>
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="{{ session('theme','light') === 'dark' ? 'dark-mode' : '' }}">
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 sidebar">
            <img src="{{ asset('images/myinternlog-logo.png') }}" alt="Logo" class="logo-img">
            <div class="welcome mb-3">Welcome, <br>{{ auth()->user()->name ?? 'Admin' }}!</div>
            <nav class="flex-grow-1 w-100">
                <a href="{{ route('admin.assign-supervisor') }}" class="nav-link {{ request()->routeIs('admin.assign-supervisor') ? 'active' : '' }}"><i class="bi bi-person-plus"></i>Assign Supervisor</a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"><i class="bi bi-people"></i>Manage Accounts</a>
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="bi bi-person"></i>Profile</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9 col-md-8 dashboard-content">
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>