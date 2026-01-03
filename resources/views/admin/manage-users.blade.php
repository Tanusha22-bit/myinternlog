@extends('layouts.admin-dashboard')

@section('title', 'Manage Account')
@section('page_icon', 'bi bi-people')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card-modern p-4 mb-4">
    <div class="d-flex mb-3 align-items-center">
        <form class="me-2 flex-grow-1" method="GET" action="{{ route('admin.users.index') }}">
            <input type="text" name="search" class="form-control" placeholder="Search Bar" value="{{ $search ?? '' }}">
        </form>
        <button class="btn btn-indigo" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add User
        </button>
    </div>
    <table class="table align-middle mb-0" style="border-radius:18px; overflow:hidden;">
        <thead style="background:#f3f4f6;">
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
                    <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Delete this user?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $users->links() }}
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
                        <input type="text" name="matric_id" class="form-control mb-3" value="{{ $user->student->student_id }}">
                        <label class="mb-1 form-label">Phone:</label>
                        <input type="text" name="student_phone" class="form-control mb-3" value="{{ $user->student->phone }}">
                    @elseif($user->role == 'university_sv' && $user->universitySupervisor)
                        <label class="mb-1 form-label">Staff ID:</label>
                        <input type="text" name="staff_id" class="form-control mb-3" value="{{ $user->universitySupervisor->staff_id }}">
                        <label class="mb-1 form-label">Department:</label>
                        <input type="text" name="department" class="form-control mb-3" value="{{ $user->universitySupervisor->department }}">
                        <label class="mb-1 form-label">Phone:</label>
                        <input type="text" name="university_phone" class="form-control mb-3" value="{{ $user->universitySupervisor->phone }}">
                    @elseif($user->role == 'industry_sv' && $user->industrySupervisor)
                        <label class="mb-1 form-label">Position:</label>
                        <input type="text" name="position" class="form-control mb-3" value="{{ $user->industrySupervisor->position }}">
                        <label class="mb-1 form-label">Company:</label>
                        <input type="text" name="company" class="form-control mb-3" value="{{ $user->industrySupervisor->company }}">
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
.form-label { font-weight: bold; }
.table th, .table td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script>
    // Show/hide extra fields based on role selection (Add User)
    document.querySelectorAll('.user-role-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var targetId = this.getAttribute('data-target');
            var target = document.querySelector(targetId);
            if (!target) return;
            var role = this.value;
            let html = '';
            if (role === 'student') {
                html = `
                    <label class="mb-1 form-label">Matric ID:</label>
                    <input type="text" name="matric_id" class="form-control mb-3" required>
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

    // Edit User Modal dynamic fields (optional: for role change in edit modal)
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
                    <input type="text" name="matric_id" class="form-control mb-3" required>
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
</script>
@endpush
@endsection