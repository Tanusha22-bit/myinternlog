@extends('layouts.admin-dashboard')

@section('title', 'Manage Account')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
         {{ session('success') }}
        </div>
    @endif

<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Manage <span class="brand-highlight">Account</span></h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>
<div class="card card-modern p-4">
    <div class="d-flex mb-3">
        <form class="me-2 flex-grow-1" method="GET" action="{{ route('admin.users.index') }}">
            <input type="text" name="search" class="form-control" placeholder="Search Bar" value="{{ $search ?? '' }}">
        </form>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
    </div>
    <table class="table table-bordered bg-white">
        <thead>
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
                    <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">View</button>
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Edit</button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>

<!-- Modals Section (outside table for proper functionality) -->
@foreach($users as $user)
<!-- View User Modal -->
<div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="viewUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #b7e3e3; border-radius: 12px; border: none;">
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="viewUserModalLabel{{ $user->id }}" style="font-weight: bold;">User Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: #009999; color: #fff; border-radius: 4px; margin-left: auto;"></button>
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
        <form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}" style="background: #b7e3e3; border-radius: 12px; border: none;">
            @csrf
            @method('PUT')
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="editUserModalLabel{{ $user->id }}" style="font-weight: bold;">Edit User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: #009999; color: #fff; border-radius: 4px; margin-left: auto;"></button>
            </div>
            <div class="modal-body">
                <label class="mb-1" style="font-weight: 500;">Name:</label>
                <input type="text" name="name" class="form-control mb-3" value="{{ $user->name }}" required>
                <label class="mb-1" style="font-weight: 500;">Role:</label>
                <select name="role" class="form-control mb-3 user-role-select-edit" data-user="{{ $user->id }}" required>
                    <option value="student" @if($user->role=='student') selected @endif>Student</option>
                    <option value="industry_sv" @if($user->role=='industry_sv') selected @endif>Industry Supervisor</option>
                    <option value="university_sv" @if($user->role=='university_sv') selected @endif>University Supervisor</option>
                    <option value="admin" @if($user->role=='admin') selected @endif>Admin</option>
                </select>
                <div id="editExtraFields{{ $user->id }}">
                    @if($user->role == 'student' && $user->student)
                        <label class="mb-1" style="font-weight: 500;">Matric ID:</label>
                        <input type="text" name="matric_id" class="form-control mb-3" value="{{ $user->student->student_id }}">
                        <label class="mb-1" style="font-weight: 500;">Phone:</label>
                        <input type="text" name="student_phone" class="form-control mb-3" value="{{ $user->student->phone }}">
                    @elseif($user->role == 'university_sv' && $user->universitySupervisor)
                        <label class="mb-1" style="font-weight: 500;">Staff ID:</label>
                        <input type="text" name="staff_id" class="form-control mb-3" value="{{ $user->universitySupervisor->staff_id }}">
                        <label class="mb-1" style="font-weight: 500;">Department:</label>
                        <input type="text" name="department" class="form-control mb-3" value="{{ $user->universitySupervisor->department }}">
                        <label class="mb-1" style="font-weight: 500;">Phone:</label>
                        <input type="text" name="university_phone" class="form-control mb-3" value="{{ $user->universitySupervisor->phone }}">
                    @elseif($user->role == 'industry_sv' && $user->industrySupervisor)
                        <label class="mb-1" style="font-weight: 500;">Position:</label>
                        <input type="text" name="position" class="form-control mb-3" value="{{ $user->industrySupervisor->position }}">
                        <label class="mb-1" style="font-weight: 500;">Company:</label>
                        <input type="text" name="company" class="form-control mb-3" value="{{ $user->industrySupervisor->company }}">
                        <label class="mb-1" style="font-weight: 500;">Phone:</label>
                        <input type="text" name="industry_phone" class="form-control mb-3" value="{{ $user->industrySupervisor->phone }}">
                    @endif
                </div>
                <label class="mb-1" style="font-weight: 500;">Email:</label>
                <input type="email" name="email" class="form-control mb-3" value="{{ $user->email }}" required>
            </div>
            <div class="modal-footer" style="border-top: none; justify-content: center;">
                <button type="submit" class="btn" style="background: #FFC107; color: #000; font-weight: bold; width: 60%;">Edit</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.users.store') }}" style="background: #b7e3e3; border-radius: 12px; border: none;">
            @csrf
            <div class="modal-header" style="border-bottom: none;">
                <h4 class="modal-title w-100 text-center" id="addUserModalLabel" style="font-weight: bold;">Add User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: #009999; color: #fff; border-radius: 4px; margin-left: auto;"></button>
            </div>
            <div class="modal-body">
                <label class="mb-1" style="font-weight: 500;">Name:</label>
                <input type="text" name="name" class="form-control mb-3" required>
                <label class="mb-1" style="font-weight: 500;">Role:</label>
                <select name="role" class="form-control mb-3 user-role-select" data-target="#addExtraFields" required>
                    <option value="student">Student</option>
                    <option value="industry_sv">Industry Supervisor</option>
                    <option value="university_sv">University Supervisor</option>
                    <option value="admin">Admin</option>
                </select>
                <div id="addExtraFields"></div>
                <label class="mb-1" style="font-weight: 500;">Email:</label>
                <input type="email" name="email" class="form-control mb-3" required>
                <label class="mb-1" style="font-weight: 500;">Password:</label>
                <input type="password" name="password" class="form-control mb-3" required>
            </div>
            <div class="modal-footer" style="border-top: none; justify-content: center;">
                <button type="submit" class="btn" style="background: #FFC107; color: #000; font-weight: bold; width: 60%;">Create</button>
            </div>
        </form>
    </div>
</div>

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
                    <label class="mb-1" style="font-weight: 500;">Matric ID:</label>
                    <input type="text" name="matric_id" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Program:</label>
                    <input type="text" name="program" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Semester:</label>
                    <input type="text" name="semester" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
                    <input type="text" name="student_phone" class="form-control mb-3">
                `;
            } else if (role === 'university_sv') {
                html = `
                    <label class="mb-1" style="font-weight: 500;">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Department:</label>
                    <input type="text" name="department" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
                    <input type="text" name="university_phone" class="form-control mb-3">
                `;
            } else if (role === 'industry_sv') {
                html = `
                    <label class="mb-1" style="font-weight: 500;">Position:</label>
                    <input type="text" name="position" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Company:</label>
                    <input type="text" name="company" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
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
                    <label class="mb-1" style="font-weight: 500;">Matric ID:</label>
                    <input type="text" name="matric_id" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Program:</label>
                    <input type="text" name="program" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Semester:</label>
                    <input type="text" name="semester" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
                    <input type="text" name="student_phone" class="form-control mb-3">
                `;
            } else if (role === 'university_sv') {
                html = `
                    <label class="mb-1" style="font-weight: 500;">Staff ID:</label>
                    <input type="text" name="staff_id" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Department:</label>
                    <input type="text" name="department" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
                    <input type="text" name="university_phone" class="form-control mb-3">
                `;
            } else if (role === 'industry_sv') {
                html = `
                    <label class="mb-1" style="font-weight: 500;">Position:</label>
                    <input type="text" name="position" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Company:</label>
                    <input type="text" name="company" class="form-control mb-3" required>
                    <label class="mb-1" style="font-weight: 500;">Phone:</label>
                    <input type="text" name="industry_phone" class="form-control mb-3">
                `;
            }
            target.innerHTML = html;
        });
    });
</script>
@endpush
@endsection