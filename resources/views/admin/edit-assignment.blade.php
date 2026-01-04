@extends('layouts.admin-dashboard')
@section('title', 'Edit Assignment')
@section('page_icon', 'bi bi-pencil-square')

@push('styles')
<style>
.avatar-xl-frame {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #6366F1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f7ff;
    overflow: hidden;
    position: relative;
}
.avatar-xl-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.form-label {
    color: #222 !important;
    font-weight: 700;
}
.btn-indigo {
    background: #6366F1;
    color: #fff !important;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    transition: background 0.2s;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4f46e5;
    color: #fff !important;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.assign-supervisor') }}" class="btn btn-danger" style="font-weight:bold;">
    <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <!-- Student Details Card (Left) -->
<div class="col-md-4 mb-4">
    <div class="card card-modern p-4 h-100" style="min-height:340px; max-height:480px;">
        <div class="d-flex align-items-center mb-3">
            <div class="avatar-xl-frame me-3">
                @if($student->profile_pic)
                    <img src="{{ asset('storage/'.$student->profile_pic) }}" alt="Avatar">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar">
                @endif
            </div>
            <div>
                <h4 class="mb-1" style="font-weight:700;">{{ $student->name }}</h4>
                <div class="mb-1"><span class="fw-bold">Matric ID:</span> {{ $student->student->student_id ?? '-' }}</div>
            </div>
        </div>
        <div class="mb-1"><span class="fw-bold">Program:</span> {{ $student->student->program ?? '-' }}</div>
        <div class="mb-1"><span class="fw-bold">Semester:</span> {{ $student->student->semester ?? '-' }}</div>
        <div class="mb-1"><span class="fw-bold">Phone:</span> {{ $student->student->phone ?? '-' }}</div>
        <div class="mb-1"><span class="fw-bold">Email:</span> {{ $student->email }}</div>
    </div>
</div>

    <!-- Internship Edit Card (Right) -->
    <div class="col-md-8 mb-4">
        <div class="card card-modern p-4 h-100">
            @if(isset($student->student->internship->offer_letter))
                <div class="mb-3">
                    <label class="form-label">Offer Letter:</label>
                    <a href="{{ asset('storage/'.$student->student->internship->offer_letter) }}" class="btn btn-success btn-sm" download>
                        <i class="bi bi-download"></i> Download Offer Letter
                    </a>
                </div>
            @endif
            <form method="POST" action="{{ $student->student->internship ? route('admin.assign-supervisor.update', $student->student->internship->id) : route('admin.assign-supervisor.store') }}">
                @csrf
                @if($student->student->internship)
                    @method('PUT')
                @else
                    <input type="hidden" name="student_id" value="{{ $student->student->id }}">
                @endif
                <div class="mb-2">
                    <label class="form-label">Company Name:</label>
                    <input type="text" name="company_name" class="form-control" value="{{ $student->student->internship->company_name ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Company Address:</label>
                    <input type="text" name="company_address" class="form-control" value="{{ $student->student->internship->company_address ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $student->student->internship->start_date ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $student->student->internship->end_date ?? '' }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Industry Supervisor:</label>
                    <select name="industry_sv_id" class="form-control" required>
                        <option value="">Select</option>
                        @foreach($industrySupervisors as $sv)
                            <option value="{{ $sv->industrySupervisor->id ?? '' }}"
                                {{ ($student->student->internship->industry_sv_id ?? '') == ($sv->industrySupervisor->id ?? '') ? 'selected' : '' }}>
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
                                {{ ($student->student->internship->university_sv_id ?? '') == ($sv->universitySupervisor->id ?? '') ? 'selected' : '' }}>
                                {{ $sv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ ($student->student->internship->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ ($student->student->internship->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="terminated" {{ ($student->student->internship->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-indigo px-4" style="font-weight:bold;">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection