@extends('layouts.admin-dashboard')

@section('title', 'Manage Communications')
@section('page_icon', 'bi bi-megaphone')

@push('styles')
<style>
.filter-card {
    min-width: 150px;
    max-width: 150px;
    min-height: 60px;
    max-height: 60px;
    border-radius: 16px;
    cursor: pointer;
    border: 2px solid #0F172A; /* Always navy outline */
    transition: border 0.2s, box-shadow 0.2s, background 0.2s, color 0.2s;
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    text-align: center;
    font-size: 1.08rem;
    padding: 0.2rem 0.2rem;
    background: #fff;
    margin-bottom: 0;
    color: #0F172A; /* Default text color */
}
.filter-card .fw-bold {
    text-decoration: none !important;
    font-size: 1.08rem;
    color: inherit;
}
.card-all,
.card-student {
    /* Both cards have the same outline now */
    border-color: #0F172A !important;
}
.filter-card.active,
.filter-card:active,
.filter-card:focus,
.filter-card:hover {
    background: #0F172A !important;
    color: #fff !important;
    border-color: #0F172A !important;
}
.filter-card.active .fw-bold,
.filter-card:active .fw-bold,
.filter-card:focus .fw-bold,
.filter-card:hover .fw-bold {
    color: #fff !important;
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
.card-modern {
    min-height: 180px;
}
.custom-thead {
    background: #0F172A !important;
}
.custom-thead th {
    color: #fff !important;
    font-weight: bold;
    vertical-align: middle;
}
.btn-indigo {
    background: #6366F1;
    color: #fff !important;
    border-radius: 999px;
    font-weight: 600;
    border: none;
    transition: background 0.2s;
    padding: 0.5rem 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-indigo:hover, .btn-indigo:focus {
    background: #4f46e5;
    color: #fff !important;
}
table thead.custom-thead, 
table thead.custom-thead tr, 
table thead.custom-thead th {
    background: #0F172A !important;
}
table thead.custom-thead th {
    color: #fff !important;
}
.table th, .table td { vertical-align: middle; }
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
@media (max-width: 575.98px) {
    .filter-card {
        min-width: 120px;
        max-width: 120px;
        font-size: 0.98rem;
        padding: 0.1rem 0.1rem;
    }
    .card-modern {
        padding: 1rem !important;
    }
}
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Row 1: Centered Cards --}}
<div class="d-flex gap-3 mb-3 justify-content-center flex-wrap flex-md-nowrap cards-row-scroll">
    <a href="{{ route('admin.communications.index', ['tab' => 'announcement', 'role' => $role]) }}"
       class="filter-card {{ $tab === 'announcement' ? 'active card-all' : 'card-all' }}">
        <div class="fw-bold">Announcements</div>
    </a>
    <a href="{{ route('admin.communications.index', ['tab' => 'date', 'role' => $role]) }}"
       class="filter-card {{ $tab === 'date' ? 'active card-student' : 'card-student' }}">
        <div class="fw-bold">Important Dates</div>
    </a>
</div>

{{-- Row 2: Centered Search Bar and Add Button --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center mb-4" style="max-width:600px;margin:0 auto;">
    <form method="GET" action="{{ route('admin.communications.index') }}" class="d-flex gap-2 align-items-center flex-grow-1 mb-2 mb-sm-0">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <select name="role" class="form-select" style="max-width:180px;">
            <option value="all" {{ $role === 'all' ? 'selected' : '' }}>All Roles</option>
            <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Students</option>
            <option value="university_sv" {{ $role === 'university_sv' ? 'selected' : '' }}>University Supervisor</option>
            <option value="industry_sv" {{ $role === 'industry_sv' ? 'selected' : '' }}>Industry Supervisor</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="btn btn-search"><i class="bi bi-search"></i></button>
    </form>
    <div>
        @if($tab === 'announcement')
            <button class="btn btn-indigo w-100 mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                <i class="bi bi-plus"></i> Add Announcement
            </button>
        @else
            <button class="btn btn-indigo w-100 mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#addDateModal">
                <i class="bi bi-plus"></i> Add Important Date
            </button>
        @endif
    </div>
</div>

{{-- Table --}}
<div class="card-modern p-4 mb-4">
    <div class="table-responsive">
        @if($tab === 'announcement')
            <table class="table align-middle mb-0">
                <thead class="custom-thead">
                    <tr>
                        <th style="width: 22%;">Title</th>
                        <th>Content</th>
                        <th style="width: 14%;">Role</th>
                        <th style="width: 12%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->title }}</td>
                        <td>{{ $announcement->content }}</td>
                        <td>{{ ucfirst($announcement->role ?? 'All') }}</td>
                        <td>
                            <button class="btn btn-outline-purple btn-sm rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#editAnnouncementModal{{ $announcement->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-pill"
                                onclick="showDeleteAnnouncementModal('{{ route('admin.communications.announcement.destroy', $announcement) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No announcements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="table align-middle mb-0">
                <thead class="custom-thead">
                    <tr>
                        <th style="width: 28%;">Title</th>
                        <th style="width: 18%;">Date</th>
                        <th style="width: 14%;">Role</th>
                        <th style="width: 12%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dates as $date)
                    <tr>
                        <td>{{ $date->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($date->date)->format('d M Y') }}</td>
                        <td>{{ ucfirst($date->role ?? 'All') }}</td>
                        <td>
                            <button class="btn btn-outline-purple btn-sm rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#editDateModal{{ $date->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-pill"
                                onclick="showDeleteDateModal('{{ route('admin.communications.date.destroy', $date) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No important dates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Add Announcement Modal --}}
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.announcement.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addAnnouncementModalLabel">Add Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all">All</option>
                        <option value="student">Students</option>
                        <option value="university_sv">University Supervisor</option>
                        <option value="industry_sv">Industry Supervisor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-indigo">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Important Date Modal --}}
<div class="modal fade" id="addDateModal" tabindex="-1" aria-labelledby="addDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.date.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addDateModalLabel">Add Important Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all">All</option>
                        <option value="student">Students</option>
                        <option value="university_sv">University Supervisor</option>
                        <option value="industry_sv">Industry Supervisor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-indigo">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Announcement Modals --}}
@foreach($announcements as $announcement)
<div class="modal fade" id="editAnnouncementModal{{ $announcement->id }}" tabindex="-1" aria-labelledby="editAnnouncementModalLabel{{ $announcement->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.announcement.update', $announcement) }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementModalLabel{{ $announcement->id }}">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" required>{{ $announcement->content }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" {{ $announcement->role == 'all' ? 'selected' : '' }}>All</option>
                        <option value="student" {{ $announcement->role == 'student' ? 'selected' : '' }}>Students</option>
                        <option value="university_sv" {{ $announcement->role == 'university_sv' ? 'selected' : '' }}>University Supervisor</option>
                        <option value="industry_sv" {{ $announcement->role == 'industry_sv' ? 'selected' : '' }}>Industry Supervisor</option>
                        <option value="admin" {{ $announcement->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-indigo">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Edit Important Date Modals --}}
@foreach($dates as $date)
<div class="modal fade" id="editDateModal{{ $date->id }}" tabindex="-1" aria-labelledby="editDateModalLabel{{ $date->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.date.update', $date) }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editDateModalLabel{{ $date->id }}">Edit Important Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $date->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->date }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" {{ $date->role == 'all' ? 'selected' : '' }}>All</option>
                        <option value="student" {{ $date->role == 'student' ? 'selected' : '' }}>Students</option>
                        <option value="university_sv" {{ $date->role == 'university_sv' ? 'selected' : '' }}>University Supervisor</option>
                        <option value="industry_sv" {{ $date->role == 'industry_sv' ? 'selected' : '' }}>Industry Supervisor</option>
                        <option value="admin" {{ $date->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-indigo">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Delete Announcement Modal --}}
<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" aria-labelledby="deleteAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteAnnouncementForm" class="modal-content" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnnouncementModalLabel">Delete Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this announcement?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Important Date Modal --}}
<div class="modal fade" id="deleteDateModal" tabindex="-1" aria-labelledby="deleteDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteDateForm" class="modal-content" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDateModalLabel">Delete Important Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this important date?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showDeleteAnnouncementModal(action) {
    document.getElementById('deleteAnnouncementForm').action = action;
    var modal = new bootstrap.Modal(document.getElementById('deleteAnnouncementModal'));
    modal.show();
}
function showDeleteDateModal(action) {
    document.getElementById('deleteDateForm').action = action;
    var modal = new bootstrap.Modal(document.getElementById('deleteDateModal'));
    modal.show();
}
</script>
@endpush