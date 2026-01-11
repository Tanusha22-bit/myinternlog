@extends('layouts.university-dashboard')

@section('title', 'My Student')

@section('styles')
<style>
    .table thead.table-primary th {
        background: #0F172A;
        color: #fff;
        border: none;
    }
    /* Circular icon buttons */
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

.btn-icon.btn-edit {
    color: #fbbf24;
    border-color: #fbbf24;
}
.btn-icon.btn-edit:hover, .btn-icon.btn-edit:focus {
    background: #fbbf24;
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

/* Card styles */
.card-modern-green {
    background: #22c55e;
    border-radius: 28px;
    box-shadow: 0 4px 24px rgba(16,185,129,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: box-shadow 0.2s;
    color: #fff !important;
}
.card-modern-green .fw-bold { color: #fff; }
.card-modern-green .display-6 { font-size: 2.2rem; font-weight: 700; color: #fff; }

.card-modern-yellow {
    background: #fbbf24;
    border-radius: 28px;
    box-shadow: 0 4px 24px rgba(251,191,36,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: box-shadow 0.2s;
    color: #fff !important;
}
.card-modern-yellow .fw-bold { color: #fff; }
.card-modern-yellow .display-6 { font-size: 2.2rem; font-weight: 700; color: #fff; }
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
    <h2 class="me-auto mb-0"><i class="bi bi-people"></i> Student <span class="brand-highlight">List</span></h2>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card-modern-green text-center w-100" style="cursor:pointer;" onclick="window.location='{{ route('supervisor.university.students') }}'">
            <div class="fw-bold mb-1" style="font-size:1.2rem;">
                <i class="bi bi-person-check" style="font-size:1.5rem;vertical-align:middle;"></i>
                Active Students
            </div>
            <div class="display-6">{{ $activeCount }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern-yellow text-center w-100" style="cursor:pointer;" onclick="window.location='{{ route('supervisor.university.history') }}'">
            <div class="fw-bold mb-1" style="font-size:1.2rem;">
                <i class="bi bi-person-x" style="font-size:1.5rem;vertical-align:middle;"></i>
                Inactive Students
            </div>
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
                        <button class="btn-icon btn-view view-details-btn" data-id="{{ $student->id }}" title="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <a href="{{ route('supervisor.university.student.reports', $student->id) }}" class="btn-icon btn-edit" title="Reports">
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