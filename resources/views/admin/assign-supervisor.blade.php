{{-- filepath: resources/views/admin/assign-supervisor.blade.php --}}
@extends('layouts.admin-dashboard')
@section('title', 'Assign Supervisor')
@section('page_icon', 'bi bi-person-plus')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex gap-3 mb-4 justify-content-center flex-wrap flex-md-nowrap cards-row-scroll">
    <a href="{{ route('admin.assign-supervisor') }}"
       class="dashboard-card bg-indigo flex-fill text-center {{ !$status ? 'active' : '' }}" style="min-width:220px;">
        <div class="fw-bold mb-1"><i class="bi bi-people"></i> All</div>
        <div class="display-6">{{ $allCount }}</div>
    </a>
    <a href="{{ route('admin.assign-supervisor', ['status' => 'active']) }}"
       class="dashboard-card bg-green flex-fill text-center {{ $status == 'active' ? 'active' : '' }}" style="min-width:220px;">
        <div class="fw-bold mb-1"><i class="bi bi-briefcase"></i> Active</div>
        <div class="display-6">{{ $activeCount }}</div>
    </a>
    <a href="{{ route('admin.assign-supervisor', ['status' => 'pending']) }}"
       class="dashboard-card bg-yellow flex-fill text-center {{ $status == 'pending' ? 'active' : '' }}" style="min-width:220px;">
        <div class="fw-bold mb-1"><i class="bi bi-hourglass-split"></i> Pending</div>
        <div class="display-6">{{ $pendingCount }}</div>
    </a>
</div>

<form method="GET" class="mb-3 d-flex flex-column flex-sm-row gap-2" action="{{ route('admin.assign-supervisor') }}">
    <input type="text" name="search" class="form-control" placeholder="Search by name, matric ID, or supervisor" value="{{ $search ?? '' }}">
    <button class="btn btn-indigo" type="submit">Search</button>
</form>

<div class="card-modern p-4 mb-4">
    <div class="table-responsive">
    <table class="table align-middle mb-0" style="border-radius:18px; overflow:hidden;">
        <thead class="custom-thead">
            <tr>
                <th>Student Name</th>
                <th>Matric ID</th>
                <th>Industry Supervisor</th>
                <th>University Supervisor</th>
                <th>Status</th>
                <th>View/Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->student?->student_id ?? '-' }}</td>
                <td>{{ $student->student?->internship?->industrySupervisor?->user?->name ?? '-' }}</td>
                <td>{{ $student->student?->internship?->universitySupervisor?->user?->name ?? '-' }}</td>
                <td>
                    @if(isset($student->student?->internship?->status))
                        @if($student->student?->internship?->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($student->student?->internship?->status == 'completed')
                            <span class="badge bg-primary">Completed</span>
                        @elseif($student->student?->internship?->status == 'terminated')
                            <span class="badge bg-danger">Terminated</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($student->student?->internship?->status) }}</span>
                        @endif
                    @else
                        <span class="badge bg-secondary">Pending</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.assign-supervisor.edit', $student->id) }}" class="btn btn-indigo btn-sm px-3">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $students->links('vendor.pagination.bootstrap-4') }}
    </div>
    </div>
</div>

<!-- All modals OUTSIDE the table -->
@foreach($students as $student)
<div class="modal fade custom-assign-modal" id="viewEditModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewEditModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ $student->student?->internship ? route('admin.assign-supervisor.update', $student->student?->internship?->id) : route('admin.assign-supervisor.store') }}">
            @csrf
            @if($student->student?->internship)
                @method('PUT')
            @else
                <input type="hidden" name="student_id" value="{{ $student->student?->id }}">
            @endif
            <div class="modal-header" style="border-bottom:none; background:transparent;">
                <h4 class="modal-title w-100 text-center" id="viewEditModalLabel{{ $student->id }}" style="font-weight: bold; color:#6366F1;">Assignment Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background:#f3f4f6; border-radius:0 0 12px 12px;">
                <div class="mb-2">
                    <label class="form-label">Student Name:</label>
                    <input type="text" class="form-control" value="{{ $student->name }}" readonly>
                </div>
                <div class="mb-2">
                    <label class="form-label">Matric ID:</label>
                    <input type="text" class="form-control" value="{{ $student->student?->student_id ?? '-' }}" readonly>
                </div>
                <div class="mb-2">
                    <label class="form-label">Company Name:</label>
                    <input type="text" name="company_name" class="form-control" value="{{ $student->student?->internship?->company_name ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Company Address:</label>
                    <input type="text" name="company_address" class="form-control" value="{{ $student->student?->internship?->company_address ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $student->student?->internship?->start_date ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $student->student?->internship?->end_date ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Industry Supervisor:</label>
                    <select name="industry_sv_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($industrySupervisors as $sv)
                            <option value="{{ $sv->industrySupervisor->id ?? '' }}"
                                {{ ($student->student?->internship?->industry_sv_id ?? '') == ($sv->industrySupervisor->id ?? '') ? 'selected' : '' }}>
                                {{ $sv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">University Supervisor:</label>
                    <select name="university_sv_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($universitySupervisors as $sv)
                            <option value="{{ $sv->universitySupervisor->id ?? '' }}"
                                {{ ($student->student?->internship?->university_sv_id ?? '') == ($sv->universitySupervisor->id ?? '') ? 'selected' : '' }}>
                                {{ $sv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ ($student->student?->internship?->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ ($student->student?->internship?->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="terminated" {{ ($student->student?->internship?->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top:none; justify-content:center; background:#f3f4f6;">
                <button type="submit" class="btn btn-indigo px-4" style="font-weight:bold;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<style>
.card-modern {
    border-radius: 22px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
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
}
.btn-indigo:hover { background: #4F46E5; }
.table th, .table td { vertical-align: middle; }
.form-label { font-weight: bold; }
.custom-assign-modal .modal-content {
    background: #f3f4f6;
    border-radius: 12px;
    border: none;
}
.custom-success-dialog {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.2);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.custom-success-dialog .dialog-content {
    background: #fff;
    border-radius: 12px;
    padding: 40px 60px;
    text-align: center;
    font-size: 1.5rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.12);
}
.custom-success-dialog .ok-btn {
    background: #FFC107;
    color: #000;
    border: none;
    border-radius: 4px;
    padding: 8px 32px;
    font-size: 1.2rem;
    margin-top: 24px;
    font-weight: bold;
}
.dashboard-card {
    border-radius: 22px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    background: #fff;
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: box-shadow 0.2s;
    text-decoration: none !important;
}
.bg-indigo { background: #6366F1; color: #fff !important; }
.bg-green { background: #22C55E; color: #fff !important; }
.bg-yellow { background: #FACC15; color: #92400E !important; }
.dashboard-card .display-6 {
    font-size: 2.2rem;
    font-weight: 700;
}
.dashboard-card .fw-bold {
    font-size: 1.1rem;
}
.dashboard-card.active, .dashboard-card:hover {
    box-shadow: 0 8px 32px rgba(99,102,241,0.18);
    filter: brightness(0.98);
}
.dashboard-card:link,
.dashboard-card:visited,
.dashboard-card:hover,
.dashboard-card:active {
    text-decoration: none !important;
}
.cards-row-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    padding-bottom: 8px;
}
.cards-row-scroll::-webkit-scrollbar {
    height: 6px;
    background: #eee;
}
.cards-row-scroll::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 4px;
}
table thead.custom-thead, 
table thead.custom-thead tr, 
table thead.custom-thead th {
    background: #0F172A !important;
}
table thead.custom-thead th {
    color: #fff !important;
}
</style>
@endsection