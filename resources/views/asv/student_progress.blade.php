@extends('layouts.university-dashboard')
@section('title', 'Student Progress')

@section('styles')
<!-- Chart.js CDN for charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .progress-bar-bg { background: #e0e7ff; border-radius: 999px; height: 20px; }
    .progress-bar-fill { background: #6366f1; border-radius: 999px; height: 20px; }
    .dashboard-card { border-radius: 18px; box-shadow: 0 2px 16px rgba(99,102,241,0.08); background: #fff; padding: 1.5rem; }
    .progress-action-btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.7rem 0;
        width: 220px;
        text-align: center;
        display: inline-block;
        border: none;
        margin-bottom: 0.5rem;
        text-decoration: none !important;
    }
    .progress-action-btn.bg-green {
        background: #22C55E;
        color: #fff !important;
    }
    .progress-action-btn.bg-yellow {
        background: #FACC15;
        color: #92400E !important;
    }   
    .progress-action-btn.bg-green:hover { background: #16a34a; }
    .progress-action-btn.bg-yellow:hover { background: #eab308; color: #fff !important; }
    .progress-action-btn.bg-indigo {
        background: #6366F1;
        color: #fff !important;
    }
    .progress-action-btn.bg-indigo:hover {
        background: #4F46E5;
    }
    .btn-pill {
        border-radius: 999px;
        font-weight: 600;
        font-size: 1rem;
        padding: 0.4rem 1.2rem;
        border: none;
        background: #6366F1;
        color: #fff !important;
        transition: background 0.2s;
    }
    .btn-pill:hover {
        background: #4F46E5;
    }
    .progress-action-btn.sm {
        font-size: 1rem;
        padding: 0.4rem 1.2rem;
        width: auto;
        min-width: 100px;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Student <span class="brand-highlight">Progress</span></h2>
</div>

<!-- Analytics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="dashboard-card flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-bar-chart"></i> Average Progress</div>
            <div class="display-6">{{ $avgProgress }}%</div>
            <div class="progress-bar-bg mt-2">
                <div class="progress-bar-fill" style="width: {{ $avgProgress }}%;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-chat-dots"></i> Feedback Given</div>
            <div class="display-6">{{ $feedbackGiven }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card flex-fill text-center">
            <div class="fw-bold mb-1"><i class="bi bi-people"></i> Total Students</div>
            <div class="display-6">{{ count($students) }}</div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name or matric ID..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button class="progress-action-btn bg-indigo sm" type="submit">
            <i class="bi bi-search"></i> Search
        </button>
    </div>
    <div class="col-md-3 text-end">
        <a href="{{ route('supervisor.university.progress.download', request()->all()) }}" class="progress-action-btn bg-green">Download Progress Report</a>
        <!-- <a href="#" class="progress-action-btn bg-yellow">Send Reminder</a> -->
    </div>
</form>

<!-- Progress Table -->
<div class="dashboard-card mb-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Matric ID</th>
                    <th>Company</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Last Report</th>
                    <th>Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progressData as $data)
                <tr>
                    <td>{{ $data['student']->student_name }}</td>
                    <td>{{ $data['student']->student_id }}</td>
                    <td>{{ $data['student']->company_name }}</td>
                    <td>
                        <div class="progress-bar-bg" style="width:100px;">
                            <div class="progress-bar-fill" style="width:{{ $data['progress'] }}%;"></div>
                        </div>
                        <span class="ms-2">{{ $data['progress'] }}%</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $data['student']->internship_status == 'active' ? 'success' : ($data['student']->internship_status == 'completed' ? 'secondary' : 'warning text-dark') }}">
                            {{ ucfirst($data['student']->internship_status) }}
                        </span>
                    </td>
                    <td>{{ $data['last_report'] }}</td>
                    <td>
                        <span class="badge bg-{{ $data['feedback'] ? 'success' : 'warning text-dark' }}">
                            {{ $data['feedback'] ? 'Given' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('supervisor.university.student.reports', $data['student']->id) }}" class="btn-pill">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Charts -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="dashboard-card">
            <div class="fw-bold mb-2"><i class="bi bi-pie-chart"></i> Students by Status</div>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dashboard-card">
            <div class="fw-bold mb-2"><i class="bi bi-graph-up"></i> Reports Submitted Over Time</div>
            <canvas id="reportsChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const statusChart = new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Completed', 'Inactive'],
        datasets: [{
            data: [
                {{ $statusCounts['active'] }},
                {{ $statusCounts['completed'] }},
                {{ $statusCounts['inactive'] }}
            ],
            backgroundColor: ['#22C55E', '#0EA5E9', '#FACC15'],
        }]
    }
});

const reportsChart = new Chart(document.getElementById('reportsChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($reportsOverTime->pluck('month')) !!},
        datasets: [{
            label: 'Reports',
            data: {!! json_encode($reportsOverTime->pluck('count')) !!},
            backgroundColor: '#6366F1'
        }]
    }
});
</script>
@endpush