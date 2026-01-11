@extends('layouts.university-dashboard')

@section('title', 'Internship History')

@section('styles')
<style>
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
    }
    .bg-indigo { background: #6366F1; color: #fff !important; }
    .bg-blue { background: #0EA5E9; color: #fff !important; }
    .bg-red { background: #EF4444; color: #fff !important; }
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
    .dashboard-card, .dashboard-card * {
        text-decoration: none !important;
    }
    .table thead.table-primary th {
        background: #0F172A;
        color: #fff;
        border: none;
    }
    .btn-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background: transparent;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
        margin-right: 8px;
        padding: 0;
    }
    .btn-icon.btn-view {
        color: #6366F1;
        border-color: #6366F1;
    }
    .btn-icon.btn-view:hover, .btn-icon.btn-view:focus {
        background: #6366F1;
        color: #fff;
    }
    .btn-icon.btn-delete {
        color: #ef4444;
        border-color: #ef4444;
    }
    .btn-icon.btn-delete:hover, .btn-icon.btn-delete:focus {
        background: #ef4444;
        color: #fff;
    }
    .brand-highlight {
        color: #6366F1;
        font-weight: 700;
        font-size: 1.2em;
    }
    .text-indigo {
        color: #6366F1;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

<div class="d-flex align-items-center mb-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2 class="me-auto mb-0"><i class="bi bi-clock-history"></i> Internship <span class="brand-highlight">History</span></h2>
</div>

<div class="d-flex gap-3 mb-4 justify-content-center flex-wrap flex-md-nowrap cards-row-scroll">
    <a href="{{ route('supervisor.university.history') }}"
       class="dashboard-card bg-indigo flex-fill text-center {{ !$status ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-people"></i> All</div>
        <div class="display-6">{{ $allCount }}</div>
    </a>
    <a href="{{ route('supervisor.university.history', ['status' => 'completed']) }}"
       class="dashboard-card bg-blue flex-fill text-center {{ $status == 'completed' ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-check-circle"></i> Completed</div>
        <div class="display-6">{{ $completedCount }}</div>
    </a>
    <a href="{{ route('supervisor.university.history', ['status' => 'terminated']) }}"
       class="dashboard-card bg-red flex-fill text-center {{ $status == 'terminated' ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-x-circle"></i> Terminated</div>
        <div class="display-6">{{ $terminatedCount }}</div>
    </a>
</div>

<div class="row mb-3">
    <div class="col-12">
        <form method="GET" action="{{ route('supervisor.university.history') }}" class="d-flex" style="max-width:100%;">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" class="form-control me-2" style="height:38px;" placeholder="Search by name, matric ID, or company" value="{{ $search ?? '' }}">
            <button class="btn btn-indigo px-4" type="submit" style="height:38px;background:#FBBF24;border-color:#FBBF24;">
                <i class="bi bi-search"></i> 
            </button>
        </form>
    </div>
</div>

<div class="card card-modern p-4 mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-radius:18px; overflow:hidden;">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Matric ID</th>
                    <th>Status</th>
                    <th>Company</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    @forelse($students as $student)
    <tr>
        <td>{{ $student->student_name }}</td>
        <td>{{ $student->student_id }}</td>
        <td>
            <span class="badge bg-{{ $student->status == 'completed' ? 'primary' : 'danger' }}">
                {{ ucfirst($student->status) }}
            </span>
        </td>
        <td>{{ $student->company_name }}</td>
        <td>
            <button class="btn-icon btn-view view-details-btn" data-id="{{ $student->id }}" title="View">
                <i class="bi bi-eye"></i>
            </button>
            <button class="btn-icon btn-delete" onclick="confirmDelete({{ $student->id }}, '{{ $student->student_name }}')" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center text-muted">No internship history found.</td>
    </tr>
    @endforelse
</tbody>
        </table>
        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-modern p-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="studentDetailsModalLabel">
            <i class="bi bi-person-badge" style="color:#6366F1;"></i>
            <span class="brand-highlight">Student Details</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0">
        <div id="studentDetailsContent"></div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header border-0">
                <h4 class="modal-title w-100 text-danger fw-bold" id="deleteConfirmModalLabel">Confirm Deletion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="deleteConfirmText" class="mb-3"></div>
                <form id="deleteStudentForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4">Delete</button>
                    <button type="button" class="btn btn-secondary ms-2 px-4" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // View details modal
    document.querySelectorAll('.view-details-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            let studentId = this.getAttribute('data-id');
            fetch(`/supervisor/university/student/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    let s = data.student;
                    let i = data.internship;
                    document.getElementById('studentDetailsContent').innerHTML = `
    <table class="table table-borderless align-middle mb-0">
        <tbody>
            <tr>
                <th class="text-indigo">Name</th>
                <td>${s.student_name}</td>
            </tr>
            <tr>
                <th class="text-indigo">Matric ID</th>
                <td>${s.student_id}</td>
            </tr>
            <tr>
                <th class="text-indigo">Program</th>
                <td>${s.program}</td>
            </tr>
            <tr>
                <th class="text-indigo">Semester</th>
                <td>${s.semester}</td>
            </tr>
            <tr>
                <th class="text-indigo">Phone</th>
                <td>${s.phone}</td>
            </tr>
            <tr>
                <th class="text-indigo">Internship Company</th>
                <td>${i.company_name}</td>
            </tr>
            <tr>
                <th class="text-indigo">Company Address</th>
                <td>${i.company_address}</td>
            </tr>
            <tr>
                <th class="text-indigo">Start Date</th>
                <td>${i.start_date}</td>
            </tr>
            <tr>
                <th class="text-indigo">End Date</th>
                <td>${i.end_date}</td>
            </tr>
            <tr>
                <th class="text-indigo">Status</th>
                <td>
                    <span class="badge bg-${i.status == 'active' ? 'success' : (i.status == 'completed' ? 'secondary' : 'warning text-dark')}">
                        ${i.status}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
`;
                    let modal = new bootstrap.Modal(document.getElementById('studentDetailsModal'));
                    modal.show();
                });
        });
    });
});
</script>
@endpush

@push('scripts')
<script>
function confirmDelete(studentId, studentName) {
    document.getElementById('deleteConfirmText').innerHTML = `Are you sure you want to delete <b>${studentName}</b> from your history?`;
    document.getElementById('deleteStudentForm').action = '/supervisor/university/history/delete/' + studentId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}
</script>
@endpush
@endsection