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
            .sidebar { border-radius: 0; }
            .dashboard-content { padding: 8px; }
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
<body>
    <!-- Mobile Menu Button -->
    <button class="btn btn-primary d-lg-none position-fixed" id="sidebarToggle"
            style="top:18px;left:18px;z-index:200;">
        <i class="bi bi-list" style="font-size:1.5rem;"></i>
    </button>
    <div class="sidebar">
        <img src="{{ asset('images/myinternlog-logo.png') }}" alt="Logo" class="logo-img">
        <div class="welcome mb-4 text-center w-100" style="font-weight:600;">
            Welcome, {{ Auth::user()->name ?? 'Name' }}!
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
    <div class="sidebar-overlay d-lg-none"></div>
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
                <!-- Notification Bell -->
        <button type="button" class="btn position-relative" id="notificationBtn" style="background:transparent;">
            <i class="bi bi-bell" style="font-size:1.5rem;"></i>
            @if(auth()->user()->unreadNotifications->count())
                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>
                <span style="font-weight:600;">
                    {{ Auth::user()->name }}
                </span>
                <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : asset('images/default-avatar.png') }}"
                     alt="Profile Picture"
                     style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid #6366F1;">
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