@extends('layouts.admin-dashboard')

@section('title', 'Manage Account')
@section('page_icon', 'bi bi-people')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row mb-4">
    <div class="col">
        <div class="d-flex gap-3 justify-content-center flex-nowrap cards-row-scroll">
            <a href="{{ route('admin.users.index', ['role' => 'all']) }}" class="dashboard-card bg-indigo flex-fill text-center {{ ($role ?? 'all') === 'all' ? 'active' : '' }}">
                <div class="fw-bold mb-1">All</div>
                <div class="display-6">{{ $counts['all'] }}</div>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="dashboard-card bg-green flex-fill text-center {{ ($role ?? '') === 'student' ? 'active' : '' }}">
                <div class="fw-bold mb-1">Students</div>
                <div class="display-6">{{ $counts['student'] }}</div>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'university_sv']) }}" class="dashboard-card bg-blue flex-fill text-center {{ ($role ?? '') === 'university_sv' ? 'active' : '' }}">
                <div class="fw-bold mb-1">University Supervisor</div>
                <div class="display-6">{{ $counts['university_sv'] }}</div>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'industry_sv']) }}" class="dashboard-card bg-yellow flex-fill text-center {{ ($role ?? '') === 'industry_sv' ? 'active' : '' }}">
                <div class="fw-bold mb-1">Industry Supervisor</div>
                <div class="display-6">{{ $counts['industry_sv'] }}</div>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="dashboard-card bg-purple flex-fill text-center {{ ($role ?? '') === 'admin' ? 'active' : '' }}">
                <div class="fw-bold mb-1">Admin</div>
                <div class="display-6">{{ $counts['admin'] }}</div>
            </a>
        </div>
    </div>
</div>

<div class="card-modern p-4 mb-4">
    <div class="d-flex flex-column flex-md-row mb-3 align-items-stretch align-items-md-center">
    <form class="me-2 flex-grow-1 d-flex" method="GET" action="{{ route('admin.users.index') }}">
            <input type="hidden" name="role" value="{{ $role ?? 'all' }}">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-search ms-2">
                <i class="bi bi-search" style="color: #111;"></i>
            </button>
        </form>
        <button class="btn btn-indigo mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add User
        </button>
    </div>

<div class="card-modern p-4 mb-4">
    <div class="table-responsive">
    <table class="table align-middle mb-0" style="border-radius:18px; overflow:hidden;">
        <thead class="custom-thead">
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <button class="btn btn-outline-purple btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-yellow btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" onclick="showDeleteModal('{{ route('admin.users.destroy', $user) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $users->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
</div>

<!-- Modals Section (outside table for proper functionality) -->
@foreach($users as $user)
<!-- View User Modal -->
<div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="viewUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-modern p-3">
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="viewUserModalLabel{{ $user->id }}" style="font-weight: bold; color:#6366F1;">User Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                @if($user->role == 'student' && $user->student)
                    <p><strong>Matric ID:</strong> {{ $user->student->student_id }}</p>
                    <p><strong>Phone:</strong> {{ $user->student->phone }}</p>
                @elseif($user->role == 'university_sv' && $user->universitySupervisor)
                    <p><strong>Staff ID:</strong> {{ $user->universitySupervisor->staff_id }}</p>
                    <p><strong>Department:</strong> {{ $user->universitySupervisor->department }}</p>
                    <p><strong>Phone:</strong> {{ $user->universitySupervisor->phone }}</p>
                @elseif($user->role == 'industry_sv' && $user->industrySupervisor)
                    <p><strong>Position:</strong> {{ $user->industrySupervisor->position }}</p>
                    <p><strong>Company:</strong> {{ $user->industrySupervisor->company }}</p>
                    <p><strong>Phone:</strong> {{ $user->industrySupervisor->phone }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content card-modern p-3" method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="editUserModalLabel{{ $user->id }}" style="font-weight: bold; color:#6366F1;">Edit User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="mb-1 form-label">Name:</label>
                <input type="text" name="name" class="form-control mb-3" value="{{ $user->name }}" required>
                <label class="mb-1 form-label">Role:</label>
                <select name="role" class="form-control mb-3 user-role-select-edit" data-user="{{ $user->id }}" required>
                    <option value="student" @if($user->role=='student') selected @endif>Student</option>
                    <option value="industry_sv" @if($user->role=='industry_sv') selected @endif>Industry Supervisor</option>
                    <option value="university_sv" @if($user->role=='university_sv') selected @endif>University Supervisor</option>
                    <option value="admin" @if($user->role=='admin') selected @endif>Admin</option>
                </select>
                <div id="editExtraFields{{ $user->id }}">
    @if($user->role == 'student' && $user->student)
        <label class="mb-1 form-label">Matric ID:</label>
        <input type="text" name="student_id" class="form-control mb-3" value="{{ $user->student->student_id }}" required>
        <label class="mb-1 form-label">Program:</label>
        <input type="text" name="program" class="form-control mb-3" value="{{ $user->student->program }}" required>
        <label class="mb-1 form-label">Semester:</label>
        <input type="text" name="semester" class="form-control mb-3" value="{{ $user->student->semester }}" required>
        <label class="mb-1 form-label">Phone:</label>
        <input type="text" name="student_phone" class="form-control mb-3" value="{{ $user->student->phone }}">
    @elseif($user->role == 'university_sv' && $user->universitySupervisor)
        <label class="mb-1 form-label">Staff ID:</label>
        <input type="text" name="staff_id" class="form-control mb-3" value="{{ $user->universitySupervisor->staff_id }}" required>
        <label class="mb-1 form-label">Department:</label>
        <input type="text" name="department" class="form-control mb-3" value="{{ $user->universitySupervisor->department }}" required>
        <label class="mb-1 form-label">Phone:</label>
        <input type="text" name="university_phone" class="form-control mb-3" value="{{ $user->universitySupervisor->phone }}">
    @elseif($user->role == 'industry_sv' && $user->industrySupervisor)
        <label class="mb-1 form-label">Position:</label>
        <input type="text" name="position" class="form-control mb-3" value="{{ $user->industrySupervisor->position }}" required>
        <label class="mb-1 form-label">Company:</label>
        <input type="text" name="company" class="form-control mb-3" value="{{ $user->industrySupervisor->company }}" required>
        <label class="mb-1 form-label">Phone:</label>
        <input type="text" name="industry_phone" class="form-control mb-3" value="{{ $user->industrySupervisor->phone }}">
    @endif
</div>
                <label class="mb-1 form-label">Email:</label>
                <input type="email" name="email" class="form-control mb-3" value="{{ $user->email }}" required>
            </div>
            <div class="modal-footer" style="border-top: none; justify-content: center;">
                <button type="submit" class="btn btn-indigo px-4" style="font-weight: bold;">Edit</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-modern p-3">
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="deleteConfirmModalLabel" style="font-weight: bold; color:#e11d48;">Confirm Deletion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer" style="border-top: none; justify-content: center;">
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4" style="font-weight: bold;">Delete</button>
                    <button type="button" class="btn btn-secondary px-4 ms-2" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content card-modern p-3" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="addUserModalLabel" style="font-weight: bold; color:#6366F1;">Add User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="mb-1 form-label">Name:</label>
                <input type="text" name="name" class="form-control mb-3" required>
                <label class="mb-1 form-label">Role:</label>
                <select name="role" class="form-control mb-3 user-role-select" data-target="#addExtraFields" required>
                    <option value="student">Student</option>
                    <option value="industry_sv">Industry Supervisor</option>
                    <option value="university_sv">University Supervisor</option>
                    <option value="admin">Admin</option>
                </select>
                <div id="addExtraFields"></div>
                <label class="mb-1 form-label">Email:</label>
                <input type="email" name="email" class="form-control mb-3" required>
                <label class="mb-1 form-label">Password:</label>
                <input type="password" name="password" class="form-control mb-3" required>
            </div>
            <div class="modal-footer" style="border-top: none; justify-content: center;">
                <button type="submit" class="btn btn-indigo px-4" style="font-weight: bold;">Create</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
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
.btn-outline-purple {
    color: #6366F1 !important;
    border: 1.5px solid #6366F1;
    background: transparent;
}
.btn-outline-purple:hover, .btn-outline-purple:focus {
    background: #6366F1;
    color: #fff !important;
}
.btn-outline-yellow {
    color: #FACC15 !important;
    border: 1.5px solid #FACC15;
    background: transparent;
}
.btn-outline-yellow:hover, .btn-outline-yellow:focus {
    background: #FACC15;
    color: #fff !important;
}
/* Make thead background dark and text white */
table thead.custom-thead, 
table thead.custom-thead tr, 
table thead.custom-thead th {
    background: #0F172A !important;
}
table thead.custom-thead th {
    color: #fff !important;
}
.form-label { font-weight: bold; }
.table th, .table td { vertical-align: middle; }
.dashboard-card {
    border-radius: 22px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    border: none;
    padding: 2rem 1.5rem;
    min-width: 160px;
    max-width: 180px;
    min-height: 100px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: box-shadow 0.2s, filter 0.2s;
    text-decoration: none !important;
    color: #fff !important;
}
.bg-indigo { background: #6366F1 !important; }
.bg-green { background: #22C55E !important; }
.bg-blue { background: #0EA5E9 !important; }
.bg-yellow { background: #FACC15 !important; color: #92400E !important; }
.bg-purple { background: #a78bfa !important; }
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
.btn-search {
    background: #fde047;
    color: #111 !important;
    border-radius: 999px;
    font-weight: 600;
    border: none;
    transition: background 0.2s;
    padding: 0.5rem 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-search:hover, .btn-search:focus {
    background: #facc15;
    color: #111 !important;
}
.btn-search i {
    color: #111 !important;
    font-size: 1.2rem;
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
</style>
@endpush

@push('scripts')
<script>
    // Show/hide extra fields based on role selection (Add User)
    document.querySelectorAll('.user-role-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var target = document.querySelector(this.getAttribute('data-target'));
            if (!target) return;
            var role = this.value;
            let html = '';
            if (role === 'student') {
                html = `
                    <label class="mb-1 form-label">Matric ID:</label>
                    <input type="text" name="student_id" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Program:</label>
                    <input type="text" name="program" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Semester:</label>
                    <input type="text" name="semester" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="student_phone" class="form-control mb-3">
                `;
            } else if (role === 'university_sv') {
                html = `
                    <label class="mb-1 form-label">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Department:</label>
                    <input type="text" name="department" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="university_phone" class="form-control mb-3">
                `;
            } else if (role === 'industry_sv') {
                html = `
                    <label class="mb-1 form-label">Position:</label>
                    <input type="text" name="position" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Company:</label>
                    <input type="text" name="company" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="industry_phone" class="form-control mb-3">
                `;
            }
            target.innerHTML = html;
        });
    });

    // Trigger change event on page load for Add User modal
    document.addEventListener('DOMContentLoaded', function() {
        var addRoleSelect = document.querySelector('#addUserModal .user-role-select');
        if (addRoleSelect) {
            addRoleSelect.dispatchEvent(new Event('change'));
        }
    });

    // Edit User Modal dynamic fields (for role change in edit modal)
    document.querySelectorAll('.user-role-select-edit').forEach(function(select) {
        select.addEventListener('change', function() {
            var userId = this.getAttribute('data-user');
            var target = document.querySelector('#editExtraFields' + userId);
            if (!target) return;
            var role = this.value;
            let html = '';
            if (role === 'student') {
                html = `
                    <label class="mb-1 form-label">Matric ID:</label>
                    <input type="text" name="student_id" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Program:</label>
                    <input type="text" name="program" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Semester:</label>
                    <input type="text" name="semester" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="student_phone" class="form-control mb-3">
                `;
            } else if (role === 'university_sv') {
                html = `
                    <label class="mb-1 form-label">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Department:</label>
                    <input type="text" name="department" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="university_phone" class="form-control mb-3">
                `;
            } else if (role === 'industry_sv') {
                html = `
                    <label class="mb-1 form-label">Position:</label>
                    <input type="text" name="position" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Company:</label>
                    <input type="text" name="company" class="form-control mb-3" required>
                    <label class="mb-1 form-label">Phone:</label>
                    <input type="text" name="industry_phone" class="form-control mb-3">
                `;
            }
            target.innerHTML = html;
        });
    });

    function showDeleteModal(actionUrl) {
        const form = document.getElementById('deleteUserForm');
        form.action = actionUrl;
        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }
</script>
@endpush
@endsection