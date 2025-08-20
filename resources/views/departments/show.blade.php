@extends('layouts.app')

@section('title', 'Department Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Department Details</h1>
                <div class="btn-group">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Department Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4 class="mt-3">{{ $department->name }}</h4>
                    <p class="text-muted">{{ $department->code }}</p>
                    <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Department Statistics</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Students:</span>
                        <strong>{{ $department->students->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Teachers:</span>
                        <strong>{{ $department->teachers->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Classes:</span>
                        <strong>{{ $department->classes->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Subjects:</span>
                        <strong>{{ $department->subjects->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Department Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Code:</strong> {{ $department->code }}</p>
                            <p><strong>English Name:</strong> {{ $department->english_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $department->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <h6 class="mt-4">Description</h6>
                    <p class="text-muted">{{ $department->description ?? 'No description available.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Related Data -->
    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="departmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                        Students ({{ $department->students->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">
                        Teachers ({{ $department->teachers->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">
                        Classes ({{ $department->classes->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab">
                        Subjects ({{ $department->subjects->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="departmentTabsContent">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($department->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Student Code</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($department->students->take(10) as $student)
                                        <tr>
                                            <td>{{ $student->student_code }}</td>
                                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $student->user->email ?? 'N/A' }}</td>
                                            <td>{{ $student->classYear->class->name ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">No students found in this department.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Teachers Tab -->
                <div class="tab-pane fade" id="teachers" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($department->teachers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Teacher Code</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Specialization</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($department->teachers->take(10) as $teacher)
                                        <tr>
                                            <td>{{ $teacher->teacher_code }}</td>
                                            <td>{{ $teacher->user->name ?? 'N/A' }}</td>
                                            <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                                            <td>{{ $teacher->specialization }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">No teachers found in this department.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Classes Tab -->
                <div class="tab-pane fade" id="classes" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($department->classes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Class Code</th>
                                            <th>Class Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($department->classes->take(10) as $class)
                                        <tr>
                                            <td>{{ $class->code }}</td>
                                            <td>{{ $class->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $class->is_active ? 'success' : 'secondary' }}">
                                                    {{ $class->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">No classes found in this department.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subjects Tab -->
                <div class="tab-pane fade" id="subjects" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            @if($department->subjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Credit Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($department->subjects->take(10) as $subject)
                                        <tr>
                                            <td>{{ $subject->code }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->credit_hours }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">No subjects found in this department.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tabs
    const triggerTabList = document.querySelectorAll('#departmentTabs button')
    triggerTabList.forEach(triggerEl => {
        new bootstrap.Tab(triggerEl)
    })
</script>
@endpush