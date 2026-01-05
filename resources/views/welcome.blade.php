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
        --primary: #6366F1; /* blue from logo */
        --primary-hover: #4F46E5;
        --accent: #FFD600;  /* yellow from logo */
        --accent-hover: #e6c200;
        --text-primary: #111827;
        --text-secondary: #6366F1;
        --muted: #64748B;
        --logo-dark: #111827;
        --logo-yellow: #FFD600;
        --logo-blue: #6366F1;
    }
    body {
        background: var(--bg-main);
        color: var(--logo-dark);
    }
    .navbar-custom {
        background: var(--bg-main);
        border-bottom: 1px solid var(--border);
    }
    .navbar-brand span {
        color: var(--logo-blue);
        font-size: 1.7rem;
        font-weight: bold;
    }
    .navbar-brand .logo-yellow {
        color: var(--logo-yellow);
    }
    .navbar-brand .logo-blue {
        color: var(--logo-blue);
    }
    .btn-primary-custom {
        background: var(--logo-blue);
        color: #fff;
        border-radius: 999px;
        border: none;
        transition: background 0.2s;
        font-weight: 600;
    }
    .btn-primary-custom:hover {
        background: var(--primary-hover);
        color: #fff;
    }
    .btn-outline-custom {
        border: 2px solid var(--logo-blue);
        color: var(--logo-blue);
        background: transparent;
        border-radius: 999px;
        transition: background 0.2s, color 0.2s;
        font-weight: 600;
    }
    .btn-outline-custom:hover {
        background: var(--logo-blue);
        color: #fff;
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
        color: #fff;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        padding: 3rem 1rem 3rem 1rem;
    }
    .hero-title {
        font-size: 2.7rem;
        font-weight: bold;
        letter-spacing: -1px;
    }
    .hero-title .logo-yellow {
        color: var(--logo-yellow);
    }
    .hero-title .logo-blue {
        color: var(--logo-blue);
    }
    .hero-subtitle {
        color: var(--logo-blue);
        font-size: 1.35rem;
        margin-bottom: 1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .hero-desc {
        color: #e0e7ef;
        max-width: 600px;
        margin: 1.5rem auto 2rem auto;
        font-size: 1.1rem;
    }
    /* Section Titles */
    .section-title {
        font-size: 2.2rem;
        font-weight: bold;
        color: var(--logo-dark);
        text-align: center;
        margin-bottom: 2rem;
        margin-top: 0.5rem;
        letter-spacing: 0.5px;
    }
    /* Cards */
    .card-custom {
        background: var(--bg-card);
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px 0 rgba(15,23,42,0.10);
        color: #fff;
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card-custom:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 32px 0 rgba(99,102,241,0.10);
    }
    .card-custom .card-title {
        color: var(--logo-blue);
        font-weight: bold;
        font-size: 1.3rem;
    }
    .card-custom .card-text {
        color: #e0e7ef;
        font-size: 1.08rem;
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
    .icon-indigo { background: var(--logo-blue); color: #fff; }
    .icon-cyan { background: var(--logo-yellow); color: var(--logo-dark); }
    .step-circle {
        background: var(--logo-blue);
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
        color: var(--logo-blue);
        text-align: center;
        font-size: 1.08rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    .roles-card {
        min-height: 260px;
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    .roles-card .card-title {
        color: var(--logo-blue);
        font-size: 1.25rem;
        font-weight: bold;
    }
    .roles-card .icon-circle {
        margin-bottom: 0.5rem;
    }
    .roles-card ul {
        margin-top: 1rem;
        color: #e0e7ef;
        font-size: 1.08rem;
    }
    .footer-custom {
        background: var(--bg-card);
        color: #e0e7ef;
        border-top: 1px solid var(--border);
        font-size: 0.98rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    .footer-custom .fw-bold {
        color: var(--logo-blue) !important;
    }
    .footer-custom a {
        color: var(--logo-blue) !important;
        font-weight: 500;
    }
    .footer-custom a:hover {
        color: var(--primary-hover) !important;
        text-decoration: underline;
    }
    @media (min-width: 768px) {
        .hero-title { font-size: 3rem; }
        .section-title { font-size: 2.4rem; }
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
            <span>"
                <span >My</span><span class="logo-blue">Intern</span><span>Log</span>
            </span>
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
        <h1 class="hero-title mb-3">
            <span >My</span><span class="logo-blue">Intern</span><span>Log</span>
        </h1>
        <div class="hero-subtitle mb-2">Empowering Internships. Simplifying Supervision. Digitalizing Logbooks.</div>
        <div class="hero-desc">
            MyInternLog is a digital platform designed for <b>students</b>, <b>university supervisors</b>, and <b>industry supervisors</b> at <b>UPSI</b>.<br>
            Record daily activities, receive feedback, and generate your official internship logbook — all in one place.
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
        <div class="section-title">Key Features</div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h5 class="card-title mb-2">Seamless Activity Logging</h5>
                    <p class="card-text">Students can log daily internship activities and milestones with ease, replacing traditional paper logbooks.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="icon-circle icon-indigo mx-auto mb-2">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h5 class="card-title mb-2">Integrated Calendar & Reminders</h5>
                    <p class="card-text">Stay on track with built-in calendar, important dates, and automated reminders for submissions and reviews.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 text-center animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="card-title mb-2">Supervisor Feedback</h5>
                    <p class="card-text">University and industry supervisors can review logs, provide feedback, and communicate directly with students.</p>
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
                    <div class="step-label">Register & Select Role</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="step-circle">2</div>
                    <div class="step-label">Log Daily Activities</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="step-circle">3</div>
                    <div class="step-label">Supervisor Reviews & Feedback</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom text-center p-3 animate__animated animate__fadeInUp animate__delay-4s">
                    <div class="step-circle">4</div>
                    <div class="step-label">Generate Official Logbook</div>
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
            <div class="col-md-4">
                <div class="card card-custom roles-card text-center animate__animated animate__fadeInLeft animate__delay-1s">
                    <div class="icon-circle icon-indigo mx-auto mb-2">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="card-title mb-2">Student</h5>
                    <ul class="list-unstyled card-text">
                        <li>Log daily internship activities</li>
                        <li>Track progress and milestones</li>
                        <li>Receive supervisor feedback</li>
                        <li>Generate digital logbook</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom roles-card text-center animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="icon-circle icon-cyan mx-auto mb-2">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5 class="card-title mb-2">University Supervisor</h5>
                    <ul class="list-unstyled card-text">
                        <li>Monitor student performance</li>
                        <li>Review and validate logs</li>
                        <li>Provide academic feedback</li>
                        <li>Track internship progress</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom roles-card text-center animate__animated animate__fadeInRight animate__delay-3s">
                    <div class="icon-circle icon-indigo mx-auto mb-2">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h5 class="card-title mb-2">Industry Supervisor</h5>
                    <ul class="list-unstyled card-text">
                        <li>Validate student activities</li>
                        <li>Assign and review tasks</li>
                        <li>Give industry feedback</li>
                        <li>Communicate with university</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-custom text-center">
    <div class="container">
        <div class="mb-2 fw-bold" style="font-size:1.1rem;">MyInternLog</div>
        <div>Final Year Project &mdash; 2025</div>
        <div>Developed by <span style="color:var(--logo-blue);font-weight:bold;">Thanusa Paranjothy</span> &middot; <span style="color:var(--logo-blue);font-weight:bold;">UPSI</span></div>
        <div class="mt-2" style="font-size:0.95rem;">
            <a href="mailto:your.email@example.com" class="text-decoration-none me-3">Contact</a>
            <a href="#" class="text-decoration-none me-3">Privacy Policy</a>
            <a href="#" class="text-decoration-none">GitHub</a>
        </div>
    </div>
</footer>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>