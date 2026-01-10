@extends('layouts.admin-dashboard')

@section('title', 'Manage Communications')
@section('page_icon', 'bi bi-megaphone')

@push('styles')
<style>
.dashboard-card {
    border-radius: 22px;
    box-shadow: 0 4px 24px rgba(99,102,241,0.10);
    border: none;
    padding: 1.5rem 1rem;
    min-width: 160px;
    max-width: 200px;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: box-shadow 0.2s, filter 0.2s;
    text-decoration: none !important;
    color: #fff !important;
    font-weight: 600;
}
.bg-indigo { background: #6366F1 !important; color: #fff !important; }
.bg-yellow { background: #FACC15 !important; color: #92400E !important; }
.bg-green { background: #22C55E !important; color: #fff !important; }
.dashboard-card .fw-bold {
    font-size: 1.1rem;
    text-decoration: none !important;
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
.card-modern {
    min-height: 180px;
}
.custom-thead {
    background: #0F172A !important;
}
.custom-thead th {
    background: #0F172A !important;
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
.action-btn {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 4px;
    border: 2px solid transparent;
    background: #fff;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
.action-btn.edit {
    border-color: #fbbf24;
    color: #fbbf24;
}
.action-btn.edit:hover, .action-btn.edit:focus {
    background: #fbbf24;
    color: #fff;
}
.action-btn.delete {
    border-color: #ef4444;
    color: #ef4444;
}
.action-btn.delete:hover, .action-btn.delete:focus {
    background: #ef4444;
    color: #fff;
}
.action-btn:active {
    opacity: 0.8;
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
       class="dashboard-card bg-indigo flex-fill text-center {{ $tab === 'announcement' ? 'active' : '' }}" style="min-width:180px;">
        <div class="fw-bold">Announcements</div>
    </a>
    <a href="{{ route('admin.communications.index', ['tab' => 'date', 'role' => $role]) }}"
       class="dashboard-card bg-yellow flex-fill text-center {{ $tab === 'date' ? 'active' : '' }}" style="min-width:180px; color:#92400E;">
        <div class="fw-bold">Important Dates</div>
    </a>
    <a href="{{ route('admin.communications.index', ['tab' => 'document']) }}"
       class="dashboard-card bg-green flex-fill text-center {{ $tab === 'document' ? 'active' : '' }}" style="min-width:180px;">
        <div class="fw-bold">Documents</div>
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
        @elseif($tab === 'date')
            <button class="btn btn-indigo w-100 mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#addDateModal">
                <i class="bi bi-plus"></i> Add Important Date
            </button>
        @elseif($tab === 'document')
            <button class="btn btn-indigo w-100 mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                <i class="bi bi-plus"></i> Add Document
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
                            <button type="button" class="action-btn edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editAnnouncementModal{{ $announcement->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="action-btn delete"
                                onclick="showDeleteConfirmModal('{{ route('admin.communications.announcement.destroy', $announcement) }}', 'announcement')">
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
            {{ $announcements->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        @elseif($tab === 'date')
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
                            <button type="button" class="action-btn edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editDateModal{{ $date->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="action-btn delete"
                                onclick="showDeleteConfirmModal('{{ route('admin.communications.date.destroy', $date) }}', 'date')">
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
            {{ $dates->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        @elseif($tab === 'document')
            <table class="table align-middle mb-0">
                <thead class="custom-thead">
                    <tr>
                        <th style="width: 30%;">Title</th>
                        <th>File</th>
                        <th style="width: 14%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-indigo btn-sm px-2 py-1" style="font-size:0.95rem;">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </td>
                        <td>
                            <button type="button" class="action-btn edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editDocumentModal{{ $doc->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="action-btn delete"
                                onclick="showDeleteConfirmModal('{{ route('admin.communications.document.destroy', $doc) }}', 'document')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No documents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $documents->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
        @endif
    </div>
</div>

{{-- Edit Announcement Modals --}}
@foreach($announcements as $announcement)
<div class="modal fade" id="editAnnouncementModal{{ $announcement->id }}" tabindex="-1" aria-labelledby="editAnnouncementModalLabel{{ $announcement->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.announcement.update', $announcement) }}">
            @csrf
            @method('PUT')
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold w-100 text-center" style="color:#6366F1;" id="editAnnouncementModalLabel{{ $announcement->id }}">Edit Announcement</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="fw-bold mb-1">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
                </div>
                <div class="mb-2">
                    <label class="fw-bold mb-1">Content</label>
                    <textarea name="content" class="form-control" required>{{ $announcement->content }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="fw-bold mb-1">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" {{ $announcement->role == 'all' ? 'selected' : '' }}>All</option>
                        <option value="student" {{ $announcement->role == 'student' ? 'selected' : '' }}>Students</option>
                        <option value="university_sv" {{ $announcement->role == 'university_sv' ? 'selected' : '' }}>University Supervisor</option>
                        <option value="industry_sv" {{ $announcement->role == 'industry_sv' ? 'selected' : '' }}>Industry Supervisor</option>
                        <option value="admin" {{ $announcement->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn w-100" style="background:#6366F1;color:#fff;border-radius:999px;font-weight:600;">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Edit Date Modals --}}
@foreach($dates as $date)
<div class="modal fade" id="editDateModal{{ $date->id }}" tabindex="-1" aria-labelledby="editDateModalLabel{{ $date->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.date.update', $date) }}">
            @csrf
            @method('PUT')
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold w-100 text-center" style="color:#facc15;" id="editDateModalLabel{{ $date->id }}">Edit Important Date</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="fw-bold mb-1">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $date->title }}" required>
                </div>
                <div class="mb-2">
                    <label class="fw-bold mb-1">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->date }}" required>
                </div>
                <div class="mb-2">
                    <label class="fw-bold mb-1">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" {{ $date->role == 'all' ? 'selected' : '' }}>All</option>
                        <option value="student" {{ $date->role == 'student' ? 'selected' : '' }}>Students</option>
                        <option value="university_sv" {{ $date->role == 'university_sv' ? 'selected' : '' }}>University Supervisor</option>
                        <option value="industry_sv" {{ $date->role == 'industry_sv' ? 'selected' : '' }}>Industry Supervisor</option>
                        <option value="admin" {{ $date->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn w-100" style="background:#facc15;color:#fff;border-radius:999px;font-weight:600;">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Edit Document Modals --}}
@foreach($documents as $doc)
<div class="modal fade" id="editDocumentModal{{ $doc->id }}" tabindex="-1" aria-labelledby="editDocumentModalLabel{{ $doc->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.document.update', $doc) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold w-100 text-center" style="color:#22C55E;" id="editDocumentModalLabel{{ $doc->id }}">Edit Document</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="fw-bold mb-1">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $doc->title }}" required>
                </div>
                <div class="mb-2">
                    <label class="fw-bold mb-1">Replace File (optional)</label>
                    <input type="file" name="file" class="form-control">
                    <small class="text-muted">Leave blank to keep current file.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn w-100" style="background:#22C55E;color:#fff;border-radius:999px;font-weight:600;">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

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

{{-- Add Document Modal --}}
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('admin.communications.document.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Add Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">File</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-indigo">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal (Reusable for all tabs) -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteConfirmForm" class="modal-content" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold w-100 text-center text-danger" id="deleteConfirmModalLabel">Confirm Deletion</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="deleteConfirmText">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="submit" class="btn" style="background:#ef4444;color:#fff;border-radius:8px;font-weight:600;width:100px;">Delete</button>
                <button type="button" class="btn btn-secondary" style="border-radius:8px;width:100px;" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showDeleteConfirmModal(action, type) {
    document.getElementById('deleteConfirmForm').action = action;
    var text = 'Are you sure you want to delete this item?';
    if(type === 'announcement') text = 'Are you sure you want to delete this announcement?';
    if(type === 'date') text = 'Are you sure you want to delete this important date?';
    if(type === 'document') text = 'Are you sure you want to delete this document?';
    document.getElementById('deleteConfirmText').innerText = text;
    var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}
</script>
@endpush

@endsection