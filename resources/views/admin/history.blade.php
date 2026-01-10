@extends('layouts.admin-dashboard')
@section('title', 'Internship History')
@section('page_icon', 'bi bi-clock-history')

@push('styles')
<style>
    .btn-indigo {
        background: #6366F1;
        color: #fff !important;
        border-radius: 999px;
        font-weight: 600;
        border: none;
        transition: background 0.2s;
    }
    .btn-indigo:hover { background: #4F46E5; }
    table thead.custom-thead, 
    table thead.custom-thead tr, 
    table thead.custom-thead th {
        background: #0F172A !important;
    }
    table thead.custom-thead th {
        color: #fff !important;
    }
    .action-btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 4px;
    }
    .action-btn.view {
    border: 2px solid #6366F1;
    color: #6366F1;
    background: #fff;
    transition: background 0.2s, color 0.2s;
}
.action-btn.view:hover, .action-btn.view:focus {
    background: #6366F1;
    color: #fff;
}

.action-btn.edit {
    border: 2px solid #fbbf24;
    color: #fbbf24;
    background: #fff;
    transition: background 0.2s, color 0.2s;
}
.action-btn.edit:hover, .action-btn.edit:focus {
    background: #fbbf24;
    color: #fff;
}

.action-btn.delete {
    border: 2px solid #ef4444;
    color: #ef4444;
    background: #fff;
    transition: background 0.2s, color 0.2s;
}
.action-btn.delete:hover, .action-btn.delete:focus {
    background: #ef4444;
    color: #fff;
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
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Filter cards -->
<div class="d-flex gap-3 mb-4 justify-content-center flex-wrap flex-md-nowrap cards-row-scroll">
    <a href="{{ route('admin.history') }}"
       class="dashboard-card bg-indigo flex-fill text-center {{ !$status ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-people"></i> All</div>
        <div class="display-6">{{ $allCount }}</div>
    </a>
    <a href="{{ route('admin.history', ['status' => 'completed']) }}"
       class="dashboard-card bg-blue flex-fill text-center {{ $status == 'completed' ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-check-circle"></i> Completed</div>
        <div class="display-6">{{ $completedCount }}</div>
    </a>
    <a href="{{ route('admin.history', ['status' => 'terminated']) }}"
       class="dashboard-card bg-red flex-fill text-center {{ $status == 'terminated' ? 'active' : '' }}" style="min-width:220px; text-decoration:none;">
        <div class="fw-bold mb-1"><i class="bi bi-x-circle"></i> Terminated</div>
        <div class="display-6">{{ $terminatedCount }}</div>
    </a>
</div>

<div class="d-flex align-items-center justify-content-end mb-4 flex-wrap">
    <form method="GET" class="d-flex gap-2" action="{{ route('admin.history') }}">
        <input type="text" name="search" class="form-control" placeholder="Search by name, matric ID, or supervisor" value="{{ $search ?? '' }}">
        <button class="btn btn-indigo" type="submit">Search</button>
    </form>
</div>

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
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->student?->student_id ?? '-' }}</td>
                    <td>{{ $student->student?->internship?->industrySupervisor?->user?->name ?? '-' }}</td>
                    <td>{{ $student->student?->internship?->universitySupervisor?->user?->name ?? '-' }}</td>
                    <td>
    @php
        $status = $student->student?->internship?->status ?? '-';
    @endphp
    @if($status === 'completed')
        <span class="badge bg-primary fw-bold">Completed</span>
    @elseif($status === 'terminated')
        <span class="badge bg-danger fw-bold">Terminated</span>
    @else
        <span class="badge bg-secondary fw-bold">{{ ucfirst($status) }}</span>
    @endif
</td>
                    <td class="text-center">
                        <!-- View Button -->
                        <button type="button" class="action-btn view" data-bs-toggle="modal" data-bs-target="#viewModal{{ $student->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Edit Button -->
                        <button type="button" class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $student->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Delete Button -->
                        <button type="button" class="action-btn delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- View Modal -->
<div class="modal fade" id="viewModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $student->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h4 class="modal-title fw-bold w-100 text-center" style="color:#6366F1;" id="viewModalLabel{{ $student->id }}">User Details</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2"><span class="fw-bold">Name:</span> {{ $student->name }}</div>
        <div class="mb-2"><span class="fw-bold">Role:</span> Student</div>
        <div class="mb-2"><span class="fw-bold">Email:</span> {{ $student->email }}</div>
        <div class="mb-2"><span class="fw-bold">Matric ID:</span> {{ $student->student?->student_id ?? '-' }}</div>
        <div class="mb-2"><span class="fw-bold">Phone:</span> {{ $student->student?->phone ?? '-' }}</div>
        <hr>
        <div class="mb-2"><span class="fw-bold">Industry Supervisor:</span> {{ $student->student?->internship?->industrySupervisor?->user?->name ?? '-' }}</div>
        <div class="mb-2"><span class="fw-bold">University Supervisor:</span> {{ $student->student?->internship?->universitySupervisor?->user?->name ?? '-' }}</div>
        <div class="mb-2"><span class="fw-bold">Status:</span> {{ ucfirst($student->student?->internship?->status ?? '-') }}</div>
      </div>
    </div>
  </div>
</div>

               <!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $student->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $student->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
        @csrf
        @method('PUT')
        <div class="modal-header border-0">
          <h4 class="modal-title fw-bold w-100 text-center" style="color:#6366F1;" id="editModalLabel{{ $student->id }}">Edit User</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="fw-bold mb-1">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">Role:</label>
            <input type="text" class="form-control" value="Student" disabled>
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">Matric ID:</label>
            <input type="text" name="student_id" class="form-control" value="{{ $student->student?->student_id ?? '' }}" required>
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">Phone:</label>
            <input type="text" name="student_phone" class="form-control" value="{{ $student->student?->phone ?? '' }}">
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
          </div>
          <hr>
          <div class="mb-2">
            <label class="fw-bold mb-1">Industry Supervisor:</label>
            <select name="industry_sv_id" class="form-control">
              @foreach($industrySupervisors as $sv)
                <option value="{{ $sv->industrySupervisor->id }}"
                  @if($student->student?->internship?->industrySupervisor?->id == $sv->industrySupervisor->id) selected @endif>
                  {{ $sv->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">University Supervisor:</label>
            <select name="university_sv_id" class="form-control">
              @foreach($universitySupervisors as $sv)
                <option value="{{ $sv->universitySupervisor->id }}"
                  @if($student->student?->internship?->universitySupervisor?->id == $sv->universitySupervisor->id) selected @endif>
                  {{ $sv->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="fw-bold mb-1">Status:</label>
            <select name="status" class="form-control">
              @foreach(['active' => 'Active', 'completed' => 'Completed', 'terminated' => 'Terminated'] as $val => $label)
                <option value="{{ $val }}"
                  @if(($student->student?->internship?->status ?? '') === $val) selected @endif>
                  {{ $label }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-indigo w-100">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-danger fw-bold w-100 text-center" id="deleteModalLabel{{ $student->id }}">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Are you sure you want to delete this student?</p>
                        <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" id="deleteForm{{ $student->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No completed internships found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $students->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if(session('success'))
    setTimeout(() => {
        document.querySelector('.alert-success').style.display = 'none';
    }, 3000);
@endif
@if(session('error'))
    setTimeout(() => {
        document.querySelector('.alert-danger').style.display = 'none';
    }, 3000);
@endif
</script>
@endpush