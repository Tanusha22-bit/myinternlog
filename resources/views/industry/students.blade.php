@extends('layouts.industry-dashboard')
@section('title', 'My Student')

@section('styles')
<style>
    .student-card, .internship-card, .supervisor-card {
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        background: #fff;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
    }
    .student-pic {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #6366F1;
        background: #EEF2FF;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        display: block;
        margin: 0 auto 1.5rem auto;
    }
    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #6366F1;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }
    .detail-list {
        list-style: none;
        padding: 0;
        margin-bottom: 0;
    }
    .detail-list li {
        margin-bottom: 0.7rem;
        font-size: 1.08rem;
        text-align: center;
    }
    @media (max-width: 991.98px) {
        .student-card, .internship-card, .supervisor-card { padding: 1.2rem 0.7rem; }
        .detail-list li { text-align: left; }
    }
</style>
@endsection

@section('content')
<h2 class="mb-4"><i class="bi bi-people"></i> My <span class="brand-highlight" >Student</span></h2>

@if($student)
<div class="row g-4">
    <!-- Left: Profile Pic & Personal Details -->
    <div class="col-md-4">
        <div class="student-card text-center">
            <img src="{{ $student->profile_pic ? asset('storage/'.$student->profile_pic) : asset('images/default-avatar.png') }}"
                 alt="Profile Picture" class="student-pic">
            <div class="section-title"><i class="bi bi-person"></i> Student Details</div>
            <ul class="detail-list">
                <li><strong>Name:</strong> {{ $student->student_name }}</li>
                <li><strong>Email:</strong> {{ $student->email }}</li>
                <li><strong>Matric ID:</strong> {{ $student->student_id }}</li>
                <li><strong>Program:</strong> {{ $student->program }}</li>
                <li><strong>Semester:</strong> {{ $student->semester }}</li>
                <li><strong>Phone:</strong> {{ $student->phone }}</li>
            </ul>
        </div>
    </div>
    <!-- Right: Internship & Supervisor Details -->
    <div class="col-md-8">
        <div class="internship-card mb-4">
            <div class="section-title"><i class="bi bi-building"></i> Internship Details</div>
            <ul class="detail-list">
                <li><strong>Company:</strong> {{ $student->company_name }}</li>
                <li><strong>Status:</strong>
                    <span class="badge bg-{{ $student->internship_status == 'active' ? 'success' : ($student->internship_status == 'completed' ? 'secondary' : 'warning text-dark') }}">
                        {{ ucfirst($student->internship_status) }}
                    </span>
                </li>
                <li><strong>Start Date:</strong> {{ $student->start_date }}</li>
                <li><strong>End Date:</strong> {{ $student->end_date }}</li>
            </ul>
        </div>
        <div class="supervisor-card">
            <div class="section-title"><i class="bi bi-person-badge"></i> University Supervisor</div>
            @if(isset($student->university_sv))
            <ul class="detail-list">
                <li><strong>Name:</strong> {{ $student->university_sv->name }}</li>
                <li><strong>Email:</strong> {{ $student->university_sv->email }}</li>
                <li><strong>Department:</strong> {{ $student->university_sv->department }}</li>
                <li><strong>Phone:</strong> {{ $student->university_sv->phone }}</li>
            </ul>
            @else
            <div class="text-muted text-center">No university supervisor assigned.</div>
            @endif
        </div>
    </div>
</div>
@else
    <div class="alert alert-warning">No student assigned to you.</div>
@endif
@endsection