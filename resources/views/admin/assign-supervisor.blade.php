@extends('layouts.admin-dashboard')
@section('title', 'Assign Supervisor')

@section('content')
@if(session('success'))
    <div id="successDialog" class="custom-success-dialog">
        <div class="dialog-content">
            <p>{{ session('success') }}</p>
            <button class="ok-btn" onclick="document.getElementById('successDialog').style.display='none'">OK</button>
        </div>
    </div>
@endif

<h2>Assign Supervisor</h2>
<table class="table table-bordered">
    <thead>
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
            <td>{{ $student->student->student_id ?? '-' }}</td>
            <td>{{ $student->internship->industrySupervisor->user->name ?? '-' }}</td>
            <td>{{ $student->internship->universitySupervisor->user->name ?? '-' }}</td>
            <td>{{ $student->internship->status ?? '-' }}</td>
            <td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewEditModal{{ $student->id }}">View/Edit</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- All modals OUTSIDE the table -->
@foreach($students as $student)
<div class="modal fade custom-assign-modal" id="viewEditModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewEditModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.assign-supervisor.update', $student->internship->id ?? 0) }}">
            @csrf
            @method('PUT')
            <div class="modal-header" style="border-bottom:none; background:transparent;">
                <h4 class="modal-title w-100 text-center" id="viewEditModalLabel{{ $student->id }}" style="font-weight: bold; color:#00796b;">Assignment Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background:#b7e3e3; border-radius:0 0 12px 12px;">
                <div class="mb-2">
                    <label>Student Name:</label>
                    <input type="text" class="form-control" value="{{ $student->name }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Matric ID:</label>
                    <input type="text" class="form-control" value="{{ $student->student->student_id ?? '-' }}" readonly>
                </div>
                <div class="mb-2">
                    <label>Company Name:</label>
                    <input type="text" name="company_name" class="form-control" value="{{ $student->internship->company_name ?? '' }}" required>
                </div>
                <div class="mb-2">
                    <label>Company Address:</label>
                    <input type="text" name="company_address" class="form-control" value="{{ $student->internship->company_address ?? '' }}" required>
                </div>
                <div class="mb-2">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $student->internship->start_date ?? '' }}" required>
                </div>
                <div class="mb-2">
                    <label>End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $student->internship->end_date ?? '' }}" required>
                </div>
                <div class="mb-2">
                    <label>Industry Supervisor:</label>
                    <select name="industry_sv_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($industrySupervisors as $sv)
                            <option value="{{ $sv->industrySupervisor->id ?? '' }}"
                                {{ ($student->internship->industry_sv_id ?? '') == ($sv->industrySupervisor->id ?? '') ? 'selected' : '' }}>
                                {{ $sv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>University Supervisor:</label>
                    <select name="university_sv_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($universitySupervisors as $sv)
                            <option value="{{ $sv->universitySupervisor->id ?? '' }}"
                                {{ ($student->internship->university_sv_id ?? '') == ($sv->universitySupervisor->id ?? '') ? 'selected' : '' }}>
                                {{ $sv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label>Status:</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ ($student->internship->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ ($student->internship->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="terminated" {{ ($student->internship->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top:none; justify-content:center; background:#b7e3e3;">
                <button type="submit" class="btn" style="background:#FFC107; color:#000; font-weight:bold; width:60%;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<style>
.custom-assign-modal .modal-content {
    background: #b7e3e3;
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
</style>
@endsection