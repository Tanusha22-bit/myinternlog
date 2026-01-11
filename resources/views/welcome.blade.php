<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MyInternLog - Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
--bg-main:#F3F4F6;
--bg-card:#111827;
--border:#1F2937;
--primary:#6366F1;
--primary-hover:#4F46E5;
--accent:#FFD600;
--logo-dark:#111827;
--logo-yellow:#FFD600;
--logo-blue:#6366F1;
}

body { background: var(--bg-main); }

.navbar-custom {
background: var(--bg-main);
border-bottom: 1px solid var(--border);
}

.btn-primary-custom {
background: var(--logo-blue);
color:#fff;
border-radius:999px;
border:none;
}

.btn-outline-custom {
border:2px solid var(--logo-blue);
color:var(--logo-blue);
border-radius:999px;
background:transparent;
}

.hero-section {
position: relative;
min-height: 420px;
background: url('{{ asset('images/hero.jpg') }}') center/cover no-repeat;
border-radius: 0 0 2rem 2rem;
}

.hero-overlay {
position:absolute;
inset:0;
background:rgba(17,24,39,.7);
}

.hero-content {
position:relative;
z-index:2;
color:#fff;
text-align:center;
padding:4rem 1rem;
max-width:720px;
margin:auto;
}

.hero-title {
font-size:3rem;
font-weight:bold;
}

.hero-subtitle {
color:var(--logo-blue);
font-size:1.3rem;
}

.hero-desc {
margin-top:1rem;
color:#e5e7eb;
font-size:1.1rem;
}

.section-title {
text-align:center;
font-size:2.2rem;
font-weight:bold;
margin-bottom:2rem;
}

.card-custom {
background: var(--bg-card);
border-radius:1.5rem;
padding:2rem;
color:#fff;
text-align:center;
}

.card-title {
color: var(--logo-blue);
font-weight:bold;
}

.icon-circle {
width:50px;
height:50px;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
margin:auto;
margin-bottom:1rem;
}

.icon-indigo { background: var(--logo-blue); }
.icon-cyan { background: var(--logo-yellow); color:#111; }

.step-circle {
width:44px;
height:44px;
background:var(--logo-blue);
color:#fff;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
margin:auto;
}

.step-label { color:var(--logo-blue); margin-top:.5rem; }

.footer-custom {
background: var(--bg-card);
color:#e5e7eb;
padding:2rem;
text-align:center;
}

.card-title-yellow {
    color: #FFD600 !important;
    font-weight: bold;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-custom navbar-expand-lg">
<div class="container">
<a class="navbar-brand d-flex align-items-center gap-2" href="#">
<img src="{{ asset('images/myinternlog-logo.png') }}" style="height:44px;">
<span><strong>My</strong><span style="color:#6366F1">Intern</span><strong>Log</strong></span>
</a>
<div>
<a href="{{ route('login') }}" class="btn btn-outline-custom me-2">Login</a>
<a href="{{ route('register') }}" class="btn btn-primary-custom">Sign Up</a>
</div>
</div>
</nav>

<!-- Hero -->
<section class="hero-section">
<div class="hero-overlay"></div>
<div class="hero-content">
<h1 class="hero-title">My<span style="color:#6366F1">Intern</span>Log</h1>
<div class="hero-subtitle">A Web-based Internship Monitoring System with Supervisor Feedback Integration </div>
<div class="hero-desc">
MyInternLog is a role-based internship management system for <b>students</b>, <b>university supervisors</b>, and <b>industry supervisors</b> at <b>UPSI</b>.
</div>
<div class="mt-4">
<a href="{{ route('register') }}" class="btn btn-primary-custom px-4 py-2 me-2">Get Started</a>
<a href="{{ route('login') }}" class="btn btn-outline-custom px-4 py-2">Login</a>
</div>
</div>
</section>

<!-- Features -->
<section class="container py-5">
<h2 class="section-title">Key Features</h2>
<div class="row g-4">

<div class="col-md-4">
<div class="card-custom">
<div class="icon-circle icon-cyan"><i class="bi bi-journal-text"></i></div>
<h5 class="card-title">Seamless Activity Logging</h5>
<p>Students record daily internship tasks, learning outcomes, and progress in structured digital reports.</p>
</div>
</div>

<div class="col-md-4">
<div class="card-custom">
<div class="icon-circle icon-indigo"><i class="bi bi-people-fill"></i></div>
<h5 class="card-title">Dual Supervisor Feedback</h5>
<p>Both university and industry supervisors can review student reports, validate activities, and provide feedback.</p>
</div>
</div>

<div class="col-md-4">
<div class="card-custom">
<div class="icon-circle icon-cyan"><i class="bi bi-file-earmark-pdf"></i></div>
<h5 class="card-title">Official Logbook (PDF)</h5>
<p>All approved reports are compiled into a professional internship logbook ready for submission to UPSI.</p>
</div>
</div>

</div>
</section>

<!-- How It Works -->
<section class="container py-5">
<h2 class="section-title">How It Works</h2>
<div class="row g-4 text-center">
<div class="col-md-3"><div class="card-custom"><div class="step-circle">1</div><div class="step-label">Register & Select Role</div></div></div>
<div class="col-md-3"><div class="card-custom"><div class="step-circle">2</div><div class="step-label">Submit Daily Internship Reports</div></div></div>
<div class="col-md-3"><div class="card-custom"><div class="step-circle">3</div><div class="step-label">Supervisors Give Feedback</div></div></div>
<div class="col-md-3"><div class="card-custom"><div class="step-circle">4</div><div class="step-label">Generate Official Logbook</div></div></div>
</div>
</section>

<!-- Roles -->
<section class="container py-5">
<h2 class="section-title">Who Is It For?</h2>
<div class="row g-4">

<div class="col-md-4">
<div class="card-custom">
<h5 class="card-title card-title-yellow">Student</h5>
<ul class="list-unstyled">
<li>Submit daily internship reports</li>
<li>View assigned tasks</li>
<li>Track supervisor feedback</li>
<li>Download official logbook (PDF)</li>
</ul>
</div>
</div>

<div class="col-md-4">
<div class="card-custom">
<h5 class="card-title card-title-yellow">University Supervisor</h5>
<ul class="list-unstyled">
<li>View assigned students</li>
<li>Review student reports</li>
<li>Provide feedback on report</li>
<li>Monitor student progress</li>
</ul>
</div>
</div>

<div class="col-md-4">
<div class="card-custom">
<h5 class="card-title card-title-yellow">Industry Supervisor</h5>
<ul class="list-unstyled">
<li>Assign internship tasks</li>
<li>View Student detail</li>
<li>Give industry feedback</li>
<li>Track student performance</li>
</ul>
</div>
</div>

</div>
</section>

<!-- Footer -->
<footer class="footer-custom">
<div class="fw-bold" style="color:#6366F1">MyInternLog</div>
<div>Final Year Project — 2025</div>
<div>Developed by Thanusa Paranjothy · UPSI</div>
</footer>

</body>
</html>
