@extends('layouts.admin-dashboard')

@section('title', 'Admin Dashboard')
@section('page_icon', 'bi bi-speedometer2')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card-modern text-center p-3">
            <div class="fw-bold mb-1">Total Students</div>
            <div class="display-6 text-success">120</div>
            <div class="text-muted">Registered</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern text-center p-3">
            <div class="fw-bold mb-1">Supervisors</div>
            <div class="display-6" style="color:#6366F1;">15</div>
            <div class="text-muted">Active</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern text-center p-3">
            <div class="fw-bold mb-1">Pending Approvals</div>
            <div class="display-6 text-warning">7</div>
            <div class="text-muted">This Week</div>
        </div>
    </div>
</div>
<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card-modern p-3">
            <div class="fw-bold mb-2"><i class="bi bi-bar-chart"></i> User Growth</div>
            <canvas id="userGrowthChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern p-3">
            <div class="fw-bold mb-2"><i class="bi bi-lightning-charge"></i> Quick Links</div>
            <div class="row g-2">
                <div class="col-12">
                    <a href="#" class="quick-link"><i class="bi bi-person-plus"></i> Add Supervisor</a>
                </div>
                <div class="col-12">
                    <a href="#" class="quick-link"><i class="bi bi-people"></i> Manage Users</a>
                </div>
                <div class="col-12">
                    <a href="#" class="quick-link"><i class="bi bi-clipboard-data"></i> Reports</a>
                </div>
                <div class="col-12">
                    <a href="#" class="quick-link"><i class="bi bi-gear"></i> Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Example Chart.js chart for User Growth
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Users',
                data: [20, 35, 50, 70, 90, 120],
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
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
@endsection