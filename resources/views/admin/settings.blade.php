@extends('layouts.admin-dashboard')

@section('title', 'Settings')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="me-auto mb-0">Settings</h2>
    <div class="avatar ms-3">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="row">
    <div class="col-md-8">
        <div class="card card-modern p-4">
            <h5 class="mb-3"><i class="bi bi-gear"></i> Preferences</h5>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Theme</label>
                    <select id="theme-select" name="theme" class="form-select">
                        <option value="light" {{ session('theme', 'light') == 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ session('theme') == 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Notifications</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="notify_feedback" id="notify_feedback" checked disabled>
                        <label class="form-check-label" for="notify_feedback">
                            Notify me when supervisor gives feedback
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="notify_task" id="notify_task" checked disabled>
                        <label class="form-check-label" for="notify_task">
                            Notify me when new task is assigned
                        </label>
                    </div>
                </div>
                <button class="btn btn-orange w-100">Save Settings</button>
            </form>
        </div>
    </div>
</div>

<!-- small script to apply theme class immediately on change (and on load) -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('theme-select');
    if (!select) return;

    function applyTheme(value) {
        document.documentElement.classList.toggle('theme-dark', value === 'dark');
        document.body.classList.toggle('dark-mode', value === 'dark');
    }

    // apply initial value from the select (uses session value rendered server-side)
    applyTheme(select.value);

    // update immediately when user changes select (before submitting)
    select.addEventListener('change', function (e) {
        applyTheme(e.target.value);
    });
});
</script>
@endpush

@endsection