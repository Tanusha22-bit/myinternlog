<!DOCTYPE html>
<html lang="en" class="{{ session('theme','light') === 'dark' ? 'theme-dark' : 'theme-light' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - MyInternLog</title>
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
            --primary-hover: #4F46E5;
            --accent: #FBBF24;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
            --muted: #9CA3AF;
        }
        body { background: var(--bg-main); }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            background: var(--sidebar);
            color: #fff;
            width: 260px;
            min-width: 260px;
            padding: 24px 0 24px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 20px 20px 0;
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
            background: rgba(251,191,36,0.18);
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
        .dashboard-content {
            padding: 40px 32px;
            margin-left: 260px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                width: 260px;
                min-width: 260px;
                height: 100vh;
                z-index: 300;
                transition: left 0.3s;
                border-radius: 0 20px 20px 0;
            }
            .sidebar.show {
                left: 0;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.25);
                z-index: 250;
            }
            .sidebar.show ~ .sidebar-overlay {
                display: block;
            }
            .dashboard-content {
                padding: 16px;
                margin-left: 0;
            }
        }
        @media (max-width: 767.98px) {
            .dashboard-content { padding: 8px; }
            .sidebar { border-radius: 0; }
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
        /* Notification Sidebar Styles */
.notification-sidebar {
    position: fixed;
    top: 0;
    right: -400px; /* Hidden by default */
    width: 350px;
    height: 100vh;
    background: #fff;
    box-shadow: -2px 0 16px rgba(99,102,241,0.12);
    border-top-left-radius: 1.5rem;
    border-bottom-left-radius: 1.5rem;
    z-index: 1050;
    transition: right 0.35s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
}
.notification-sidebar.active {
    right: 0;
}
.sidebar-header {
    border-bottom: 1px solid #e5e7eb;
}
.sidebar-body {
    overflow-y: auto;
    flex: 1 1 auto;
}
    </style>
   @yield('styles')
</head>
<body class="{{ session('theme','light') === 'dark' ? 'dark-mode' : '' }}">
    <!-- Mobile Menu Button -->
    <button class="btn btn-primary d-lg-none position-fixed" id="sidebarToggle"
            style="top:18px;left:18px;z-index:200;">
        <i class="bi bi-list" style="font-size:1.5rem;"></i>
    </button>
    <div class="sidebar">
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
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </div>
    <div class="sidebar-overlay d-lg-none"></div>
    <div class="d-flex align-items-center justify-content-between px-4 py-3"
     style="background: #f7f8fa; border-bottom: 1px solid #eee; margin-left:260px;">
    <div>
        @yield('page-title')
    </div>
    <div class="d-flex align-items-center">
                <!-- Notification Bell -->
        <button type="button" class="btn position-relative" id="notificationBtn" style="background:transparent;">
            <i class="bi bi-bell" style="font-size:1.5rem;"></i>
            @if(auth()->user()->unreadNotifications->count())
                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>
        <span class="me-3 fw-semibold" style="font-size:1.1rem;">
            {{ auth()->user()->name ?? 'User' }}
        </span>
        <img src="{{ auth()->user()->profile_pic ? asset('storage/' . auth()->user()->profile_pic) : asset('images/default-avatar.png') }}"
             alt="Profile Picture"
             class="rounded-circle"
             style="width:44px; height:44px; object-fit:cover; border:2px solid #6366F1;">
    </div>
</div>
<!-- Notification Sidebar -->
<div id="notificationSidebar" class="notification-sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center px-4 py-3">
        <h5 class="mb-0">Notifications</h5>
        <button type="button" class="btn-close" aria-label="Close" id="closeSidebarBtn"></button>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <h6 class="px-2 pt-2">Unread</h6>
        <ul class="list-group list-group-flush mb-3">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <li class="list-group-item">
                    <a href="{{ $notification->data['url'] ?? '#' }}"
                       class="text-decoration-none"
                       onclick="markAsRead(event, '{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @empty
                <li class="list-group-item text-center text-muted">No unread notifications.</li>
            @endforelse
        </ul>
        <h6 class="px-2 pt-2">Viewed</h6>
        <ul class="list-group list-group-flush">
            @forelse(auth()->user()->readNotifications as $notification)
                <li class="list-group-item">
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="text-decoration-none text-muted">
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @empty
                <li class="list-group-item text-center text-muted">No viewed notifications.</li>
            @endforelse
        </ul>
    </div>
</div>
    <div class="dashboard-content">
        @yield('content')
    </div>
    @stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar (main menu) toggle
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    if(toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Notification Sidebar toggle
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationSidebar = document.getElementById('notificationSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    if (notificationBtn && notificationSidebar) {
        notificationBtn.addEventListener('click', function() {
            notificationSidebar.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    if (closeSidebarBtn && notificationSidebar) {
        closeSidebarBtn.addEventListener('click', function() {
            notificationSidebar.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});

// Mark notification as read, move to "Viewed", update badge, then redirect
function markAsRead(event, notificationId, url) {
    event.preventDefault();
    fetch('/notifications/read/' + notificationId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(response => {
        if(response.ok) {
            // Move the notification to the "Viewed" section
            const notifElem = event.target.closest('li');
            const viewedList = document.querySelector('.sidebar-body ul.list-group:last-of-type');
            if (notifElem && viewedList) {
                const link = notifElem.querySelector('a');
                if(link) link.classList.add('text-muted');
                viewedList.appendChild(notifElem);
            }

            // Update badge count in header
            let badge = document.getElementById('notificationBadge');
            if (badge) {
                let count = parseInt(badge.textContent.trim());
                if (count > 1) {
                    badge.textContent = count - 1;
                } else {
                    badge.style.display = 'none';
                }
            }

            // Wait 500ms before redirecting so user sees the update
            setTimeout(function() {
                window.location.href = url;
            }, 500);
        }
    });
}
</script>
</body>
</html>