@extends('layouts.university-dashboard')

@section('title', 'My Student')

@section('styles')
<style>
    .table thead.table-primary th {
        background: #0F172A;
        color: #fff;
        border: none;
    }
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
        border: none;
        border-radius: 999px;
        font-weight: 500;
        transition: background 0.2s;
    }
    .btn-indigo:hover, .btn-indigo:focus {
        background: #4F46E5;
        color: #fff !important;
    }
    .card-modern {
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(99,102,241,0.08);
        background: #fff;
    }
    #studentDetailsContent strong {
        color: #6366F1;
        font-weight: 600;
    }
    #studentDetailsContent .badge {
        font-size: 1rem;
        vertical-align: middle;
    }
    .modal-content.card-modern {
        padding: 2rem;
    }
    .text-indigo {
        color: #6366F1;
        font-weight: 600;
    }
    .filter-card {
        transition: box-shadow 0.2s, border 0.2s;
        border: 2px solid transparent;
    }
    .filter-card:hover {
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        border-color: #6366F1;
    }
</style>
@endsection

@section('content')
@stack('scripts')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Student <span class="brand-highlight">List</span></h2>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ !$statusFilter ? 'border-primary shadow' : '' }}" style="cursor:pointer;" onclick="window.location='{{ route('supervisor.university.students') }}'">
            <div class="fw-bold text-primary mb-1"><i class="bi bi-people"></i> All Students</div>
            <div class="display-6">{{ $allCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ $statusFilter === 'active' ? 'border-success shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?status=active'">
            <div class="fw-bold text-success mb-1"><i class="bi bi-person-check"></i> Active Students</div>
            <div class="display-6">{{ $activeCount }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern text-center p-3 filter-card {{ $statusFilter === 'inactive' ? 'border-warning shadow' : '' }}" style="cursor:pointer;" onclick="window.location='?status=inactive'">
            <div class="fw-bold text-secondary mb-1"><i class="bi bi-person-x"></i> Inactive Students</div>
            <div class="display-6">{{ $inactiveCount }}</div>
        </div>
    </div>
</div>

<div class="card card-modern p-4 mb-4">
    <input type="text" class="form-control mb-3" id="studentSearch" placeholder="Search student by name or ID...">
    <div class="table-responsive">
        <table class="table align-middle" style="border-radius: 12px; overflow: hidden;">
            <thead class="table-primary">
                <tr>
                    <th><i class="bi bi-person"></i> Name</th>
                    <th><i class="bi bi-credit-card-2-front"></i> Matric ID</th>
                    <th><i class="bi bi-activity"></i> Status</th>
                    <th><i class="bi bi-building"></i> Company</th>
                    <th><i class="bi bi-eye"></i> Action</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                @foreach($students as $student)
                <tr>
                    <td class="fw-semibold">{{ $student->student_name }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>
                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : ($student->status == 'completed' ? 'secondary' : 'warning text-dark') }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                    <td>{{ $student->company_name }}</td>
                    <td>
                        <button class="btn btn-indigo btn-sm view-details-btn" data-id="{{ $student->id }}">
                            <i class="bi bi-eye"></i> 
                        </button>
                            <a href="{{ route('supervisor.university.student.reports', $student->id) }}" class="btn btn-indigo btn-sm">
                            <i class="bi bi-journal-text"></i> 
                            </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $students->links() }}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Search filter
    document.getElementById('studentSearch').addEventListener('input', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('#studentTable tr').forEach(function(row) {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

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
@endsection