@extends('layouts.app')

@section('title', 'Academic Years Settings')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Academic Years Management</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Academic Year
                    </button>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($academicYears as $year)
                        <tr>
                            <td>
                                <strong>{{ $year->name }}</strong>
                                @if($year->is_current)
                                    <span class="badge bg-success ms-2">Current</span>
                                @endif
                            </td>
                            <td>{{ $year->code }}</td>
                            <td>{{ $year->start_date->format('M d, Y') }}</td>
                            <td>{{ $year->end_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $year->is_active ? 'success' : 'secondary' }}">
                                    {{ $year->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if(!$year->is_current)
                                    <form action="{{ route('settings.set-current-academic-year', $year) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                data-bs-toggle="tooltip" title="Set as Current">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#editAcademicYearModal"
                                            data-bs-id="{{ $year->id }}"
                                            data-bs-name="{{ $year->name }}"
                                            data-bs-code="{{ $year->code }}"
                                            data-bs-start-date="{{ $year->start_date->format('Y-m-d') }}"
                                            data-bs-end-date="{{ $year->end_date->format('Y-m-d') }}"
                                            data-bs-is-active="{{ $year->is_active }}"
                                            data-bs-is-current="{{ $year->is_current }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x display-4 d-block mb-2"></i>
                                    No academic years found.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Academic Year Modal -->
<div class="modal fade" id="addAcademicYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Academic Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('settings.academic-years.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Code *</label>
                        <input type="text" class="form-control" id="code" name="code" required maxlength="10">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_current" name="is_current" value="1">
                            <label class="form-check-label" for="is_current">Set as current academic year</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Academic Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Academic Year Modal -->
<div class="modal fade" id="editAcademicYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Academic Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editAcademicYearForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Code *</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required maxlength="10">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_current" name="is_current" value="1">
                            <label class="form-check-label" for="edit_is_current">Set as current academic year</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Academic Year</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Edit modal handling
    const editModal = document.getElementById('editAcademicYearModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-bs-id');
        const name = button.getAttribute('data-bs-name');
        const code = button.getAttribute('data-bs-code');
        const startDate = button.getAttribute('data-bs-start-date');
        const endDate = button.getAttribute('data-bs-end-date');
        const isActive = button.getAttribute('data-bs-is-active') === '1';
        const isCurrent = button.getAttribute('data-bs-is-current') === '1';

        // Update form action
        const form = editModal.querySelector('#editAcademicYearForm');
        form.action = `/settings/academic-years/${id}`;

        // Populate form fields
        editModal.querySelector('#edit_name').value = name;
        editModal.querySelector('#edit_code').value = code;
        editModal.querySelector('#edit_start_date').value = startDate;
        editModal.querySelector('#edit_end_date').value = endDate;
        editModal.querySelector('#edit_is_active').checked = isActive;
        editModal.querySelector('#edit_is_current').checked = isCurrent;
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush