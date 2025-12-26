<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyInternLog - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
    :root {
        --bg-main: #F3F4F6;
        --bg-card: #111827;
        --border: #1F2937;
        --primary: #6366F1;
        --primary-hover: #4F46E5;
        --accent: #22D3EE;
        --text-primary: #F9FAFB;
        --text-secondary: #9CA3AF;
        --muted: #64748B;
    }
    body {
        background: var(--bg-main);
        color: #22223b;
    }
    .navbar-custom {
        background: var(--bg-main);
        border-bottom: 1px solid var(--border);
    }
    .navbar-brand span {
        color: var(--primary);
    }
    .btn-primary-custom {
        background: var(--primary);
        color: #fff;
        border-radius: 999px;
        border: none;
        transition: background 0.2s;
    }
    .btn-primary-custom:hover {
        background: var(--primary-hover);
        color: #fff;
    }
    .btn-outline-custom {
        border: 1px solid #475569;
        color: var(--primary);
        background: transparent;
        border-radius: 999px;
        transition: background 0.2s, color 0.2s;
    }
    .btn-outline-custom:hover {
        background: #e0e7ef;
        color: var(--primary-hover);
    }
    /* Hero Section */
    .hero-section {
        position: relative;
        min-height: 420px;
        background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 2rem 2rem;
        margin-bottom: 0;
        overflow: hidden;
    }
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(17,24,39,0.7);
        z-index: 1;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--text-primary);
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        padding: 3rem 1rem 3rem 1rem;
    }
    .hero-title {
        font-size: 2.7rem;
        font-weight: bold;
        color: #fff;
        letter-spacing: -1px;
    }
    .hero-title .highlight {
        color: var(--primary);
    }
    .hero-subtitle {
        color: var(--text-secondary);
        font-size: 1.35rem;
        margin-bottom: 1rem;
    }
    .hero-desc {
        color: var(--muted);
        max-width: 600px;
        margin: 1.5rem auto 2rem auto;
    }
    /* Section Titles */
    .section-title {
        font-size: 2rem;
        font-weight: bold;
        color: #22223b;
        text-align: center;
        margin-bottom: 2rem;
        margin-top: 0.5rem;
    }
    /* Cards */
    .card-custom {
        background: var(--bg-card);
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px 0 rgba(15,23,42,0.10);
        color: var(--text-primary);
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card-custom:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 32px 0 rgba(99,102,241,0.10);
    }
    .card-custom .card-title {
        color: var(--primary);
    }
    .card-custom .card-text {
        color: var(--text-secondary);
    }
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-bottom: 1rem;
        font-size: 2rem;
    }
    .icon-indigo { background: var(--primary); color: #fff; }
    .icon-cyan { background: var(--accent); color: #0F172A; }
    .step-circle {
        background: var(--primary);
        color: #fff;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        margin: 0 auto 0.5rem auto;
    }
    .step-label {
        color: var(--muted);
        text-align: center;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .roles-card {
        min-height: 260px;
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    .footer-custom {
        background: var(--bg-card);
        color: var(--text-secondary);
        border-top: 1px solid var(--border);
        font-size: 0.98rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    @media (min-width: 768px) {
        .hero-title { font-size: 3rem; }
        .section-title { font-size: 2.2rem; }
    }
    .section-space {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-custom navbar-expand-lg mb-0">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="{{ asset('images/myinternlog-logo.png') }}" alt="Logo" style="height: 44px;" class="me-2">
            <span class="fw-bold">My<span>Intern</span>Log</span>
        </a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-custom me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary-custom">Sign Up</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section animate__animated animate__fadeInDown">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title mb-3">My<span class="highlight">Intern</span>Log</h1>
        <div class="hero-subtitle mb-2">Your Smart Digital Internship Logbook</div>
        <div class="hero-desc">
            Record daily activities, receive supervisor feedback, and generate your internship logbook — all in one platform.
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-3">
            <a href="{{ route('register') }}" class="btn btn-primary-custom px-4 py-2 fw-semibold">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-outline-custom px-4 py-2 fw-semibold">Login</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-space">
    <div class="container">
        <div class="section-title">Features</div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h5 class="card-title mb-2">Easy Activity Logging</h5>
                    <p class="card-text">Log your daily internship activities and milestones with ease.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="icon-circle icon-indigo mx-auto mb-2">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h5 class="card-title mb-2">Important Dates</h5>
                    <p class="card-text">Never miss a deadline with built-in calendar and reminders.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="card-title mb-2">Supervisor Feedback</h5>
                    <p class="card-text">Get timely feedback and communicate with your supervisor.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="section-space">
    <div class="container">
        <div class="section-title">How It Works</div>
        <div class="row justify-content-center align-items-center g-4">
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="step-circle">1</div>
                    <div class="step-label">Register Account</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="step-circle">2</div>
                    <div class="step-label">Submit Daily Logbook</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="step-circle">3</div>
                    <div class="step-label">Supervisor Reviews</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-4s">
                    <div class="step-circle">4</div>
                    <div class="step-label">Generate PDF</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Roles Section -->
<section class="section-space">
    <div class="container">
        <div class="section-title">Who Is It For?</div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card card-custom roles-card text-center animate__animated animate__fadeInLeft animate__delay-1s">
                    <div class="icon-circle icon-indigo mx-auto mb-2">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="card-title mb-2">Student</h5>
                    <ul class="list-unstyled card-text">
                        <li>Submit daily reports</li>
                        <li>View tasks</li>
                        <li>Track progress</li>
                        <li>Generate logbook</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom roles-card text-center animate__animated animate__fadeInRight animate__delay-2s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h5 class="card-title mb-2">Supervisor</h5>
                    <ul class="list-unstyled card-text">
                        <li>Review reports</li>
                        <li>Give feedback</li>
                        <li>Track students</li>
                        <li>Assign tasks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="section-space">
    <div class="container text-center">
        <div class="card card-custom p-4 mx-auto mb-3 animate__animated animate__zoomIn" style="max-width: 600px;">
            <h2 class="mb-3" style="color:var(--primary)">Ready to digitalize your internship logbook?</h2>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary-custom px-4 py-2 fw-semibold">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-outline-custom px-4 py-2 fw-semibold">Login</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-custom text-center">
    <div class="container">
        <div class="mb-2 fw-bold" style="color:var(--primary);font-size:1.1rem;">MyInternLog</div>
        <div>Final Year Project &mdash; 2025</div>
        <div>Developed by [Your Name] &middot; [Your University]</div>
        <div class="mt-2" style="font-size:0.95rem;">
            <a href="mailto:your.email@example.com" class="text-secondary text-decoration-none me-3">Contact</a>
            <a href="#" class="text-secondary text-decoration-none me-3">Privacy Policy</a>
            <a href="#" class="text-secondary text-decoration-none">GitHub</a>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>