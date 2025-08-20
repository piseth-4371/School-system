@extends('layouts.app')

@section('title', 'Class Year Management')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Class Year Management</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassYearModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Class Year
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Department</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classYears as $classYear)
                        <tr>
                            <td>{{ $classYear->class->name ?? 'N/A' }}</td>
                            <td>{{ $classYear->class->department->name ?? 'N/A' }}</td>
                            <td>{{ $classYear->academicYear->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($classYear->semester) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $classYear->is_active ? 'success' : 'secondary' }}">
                                    {{ $classYear->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#editClassYearModal"
                                            data-bs-id="{{ $classYear->id }}"
                                            data-bs-class-id="{{ $classYear->class_id }}"
                                            data-bs-year-id="{{ $classYear->year_id }}"
                                            data-bs-semester="{{ $classYear->semester }}"
                                            data-bs-is-active="{{ $classYear->is_active }}">
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
                                    No class years found.
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

<!-- Add Class Year Modal -->
<div class="modal fade" id="addClassYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Class Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('settings.class-years.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class *</label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-department="{{ $class->department->name ?? 'N/A' }}">
                                    {{ $class->name }} ({{ $class->department->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted" id="department-info">
                            Department will be automatically set based on the selected class
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="year_id" class="form-label">Academic Year *</label>
                        <select class="form-select" id="year_id" name="year_id" required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}">{{ $academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester *</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="first">First Semester</option>
                            <option value="second">Second Semester</option>
                            <option value="summer">Summer Semester</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Class Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Class Year Modal -->
<div class="modal fade" id="editClassYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Class Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editClassYearForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_class_id" class="form-label">Class *</label>
                        <select class="form-select" id="edit_class_id" name="class_id" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-department="{{ $class->department->name ?? 'N/A' }}">
                                    {{ $class->name }} ({{ $class->department->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Department will be automatically set based on the selected class
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_year_id" class="form-label">Academic Year *</label>
                        <select class="form-select" id="edit_year_id" name="year_id" required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}">{{ $academicYear->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_semester" class="form-label">Semester *</label>
                        <select class="form-select" id="edit_semester" name="semester" required>
                            <option value="first">First Semester</option>
                            <option value="second">Second Semester</option>
                            <option value="summer">Summer Semester</option>
                        </select>
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
                    <button type="submit" class="btn btn-primary">Update Class Year</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show department info when class is selected
    document.getElementById('class_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const departmentName = selectedOption.getAttribute('data-department');
        document.getElementById('department-info').textContent = 
            `Department: ${departmentName}`;
    });

    // Edit modal handling
    const editModal = document.getElementById('editClassYearModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-bs-id');
        const classId = button.getAttribute('data-bs-class-id');
        const yearId = button.getAttribute('data-bs-year-id');
        const semester = button.getAttribute('data-bs-semester');
        const isActive = button.getAttribute('data-bs-is-active') === '1';

        // Update form action
        const form = editModal.querySelector('#editClassYearForm');
        form.action = `/settings/class-years/${id}`;

        // Populate form fields
        document.getElementById('edit_class_id').value = classId;
        document.getElementById('edit_year_id').value = yearId;
        document.getElementById('edit_semester').value = semester;
        document.getElementById('edit_is_active').checked = isActive;

        // Show department info for selected class
        const selectedOption = document.querySelector(`#edit_class_id option[value="${classId}"]`);
        if (selectedOption) {
            const departmentName = selectedOption.getAttribute('data-department');
            const departmentInfo = document.createElement('small');
            departmentInfo.className = 'form-text text-muted';
            departmentInfo.textContent = `Department: ${departmentName}`;
            
            // Remove existing department info if any
            const existingInfo = document.querySelector('#editClassYearModal .form-text.text-muted');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            // Add new department info
            document.getElementById('edit_class_id').parentNode.appendChild(departmentInfo);
        }
    });
</script>
@endpush