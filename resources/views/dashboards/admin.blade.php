@extends('layouts.admin-dashboard')

@section('title', 'Admin Dashboard')
@section('page_icon', 'bi bi-speedometer2')

@push('styles')
<style>

.card-modern {
    border-radius: 22px;
    box-shadow: 0 2px 16px rgba(99,102,241,0.07);
    background: #fff;
    border: none;
}
.btn-indigo {
    background: #6366F1;
    color: #fff !important;
    border-radius: 999px;
    font-weight: 600;
    border: none;
    transition: background 0.2s;
    padding: 0.5rem 1.2rem;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4f46e5;
    color: #fff !important;
}
.quick-action-btn {
    width: 100%;
    min-width: 180px;
    min-height: 48px;
    font-size: 1.08rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.btn-indigo, .btn-success {
    min-width: 220px;
    font-weight: 600;
    border-radius: 12px;
}

</style>
@endpush

@section('content')
<div class="d-flex justify-content-center align-items-start gap-4 mb-4 flex-wrap">
    <!-- Total Users Card (Left) -->
    <div class="card-modern text-center p-3 d-flex flex-column justify-content-center" style="min-width:260px; max-width:300px; min-height:240px; background: #f5f7ff; border: 2px solid #6366F1;">
        <div class="fw-bold mb-2" style="font-size:1.2rem;">Total Users</div>
        <div class="display-4" style="color:#6366F1;">{{ $totalUsers }}</div>
        <div class="d-flex justify-content-between mt-3" style="font-size:0.98rem;">
            <div class="text-muted text-start">
                <div><span style="color:#6366F1;">●</span> Students</div>
                <div><span style="color:#22c55e;">●</span> Industry SV</div>
                <div><span style="color:#f59e42;">●</span> University SV</div>
                <div><span style="color:#0F172A;">●</span> Admins</div>
            </div>
            <div class="text-end" style="font-weight:500;">
                <div>{{ $totalStudents }}</div>
                <div>{{ $totalIndustrySV }}</div>
                <div>{{ $totalUniversitySV }}</div>
                <div>{{ $totalAdmins }}</div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards (Middle, 2x2 grid) -->
    <div class="d-flex flex-column gap-3">
        <div class="d-flex gap-3">
            <div class="card-modern text-center p-3" style="min-width:160px; max-width:170px;">
                <div class="fw-bold mb-1">Announcements</div>
                <div class="display-6 text-info">{{ $totalAnnouncements }}</div>
            </div>
            <div class="card-modern text-center p-3" style="min-width:160px; max-width:170px;">
                <div class="fw-bold mb-1">Important Dates</div>
                <div class="display-6 text-success">{{ $totalDates }}</div>
            </div>
        </div>
        <div class="d-flex gap-3">
            <div class="card-modern text-center p-3" style="min-width:160px; max-width:170px;">
                <div class="fw-bold mb-1">Active Internships</div>
                <div class="display-6 text-warning">{{ $activeInternships }}</div>
            </div>
            <div class="card-modern text-center p-3" style="min-width:160px; max-width:170px;">
                <div class="fw-bold mb-1">Pending Assignments</div>
                <div class="display-6 text-danger">{{ $pendingAssignments }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card (Right) -->
    <div class="card-modern text-center p-3 d-flex flex-column justify-content-center" style="min-width:260px; max-width:300px; min-height:240px; background: #f5f7ff; border: 2px solid #6366F1;">
        <div class="fw-bold mb-3 text-center">Quick Actions</div>
        <div class="d-flex flex-column gap-3 w-100">
            <a href="{{ route('admin.communications.index', ['tab' => 'announcement']) }}" class="btn btn-indigo quick-action-btn">
                <i class="bi bi-megaphone"></i> Add Announcement
            </a>
            <a href="{{ route('admin.communications.index', ['tab' => 'date']) }}" class="btn btn-success quick-action-btn">
                <i class="bi bi-calendar-plus"></i> Add Important Date
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-warning text-white quick-action-btn">
                <i class="bi bi-person-plus"></i> Add User
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-5">
        <div class="card-modern p-3" style="min-height: 430px;">
            <div class="fw-bold mb-2"><i class="bi bi-pie-chart"></i> User Roles Distribution</div>
            <canvas id="rolesPieChart" height="90"></canvas>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card-modern p-3 d-flex flex-column justify-content-between" style="min-height: 430px;">
            <div>
                <div class="fw-bold mb-2"><i class="bi bi-bar-chart"></i> Registrations Over Time</div>
                <canvas id="registrationsChart" height="120"></canvas>
            </div>
            <!-- Downloadable Reports under the line chart, centered -->
            <div class="mt-4 text-center">
                <div class="fw-bold mb-2"><i class="bi bi-download"></i> Downloadable Reports</div>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('admin.download.assignments.csv') }}" class="btn btn-indigo me-2 mb-2">
                        <i class="bi bi-file-earmark-excel"></i> Internship Assignments (CSV)
                    </a>
                    <a href="{{ route('admin.download.analytics.csv') }}" class="btn btn-success mb-2">
                        <i class="bi bi-file-earmark-excel"></i> Analytics Summary (CSV)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart for User Roles
    new Chart(document.getElementById('rolesPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Students', 'Industry SV', 'University SV', 'Admins'],
            datasets: [{
                data: [{{ $totalStudents }}, {{ $totalIndustrySV }}, {{ $totalUniversitySV }}, {{ $totalAdmins }}],
                backgroundColor: ['#6366F1', '#22c55e', '#f59e42', '#0F172A']
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Bar/Line Chart for Registrations
    new Chart(document.getElementById('registrationsChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($registrationLabels) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($registrationData) !!},
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#6366F1'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush