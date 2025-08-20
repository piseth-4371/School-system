@extends('layouts.app')

@section('title', 'Student Reports')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Student Reports</h1>
                <div class="btn-group">
                    <a href="{{ route('reports.export-students') }}" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>Export CSV
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.students') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select" id="department_id" name="department_id">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="class_year_id" class="form-label">Class Year</label>
                    <select class="form-select" id="class_year_id" name="class_year_id">
                        <option value="">All Classes</option>
                        @foreach($classYears as $classYear)
                            <option value="{{ $classYear->id }}" {{ request('class_year_id') == $classYear->id ? 'selected' : '' }}>
                                {{ $classYear->class->name ?? 'N/A' }} - {{ $classYear->academicYear->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Student Report Results
                <span class="badge bg-primary ms-2">{{ $students->count() }} records found</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Student Code</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Class</th>
                            <th>Academic Year</th>
                            <th>Phone</th>
                            <th>Enrollment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->student_code }}</strong></td>
                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                            <td>{{ $student->user->email ?? 'N/A' }}</td>
                            <td>{{ ucfirst($student->gender) }}</td>
                            <td>{{ $student->department->name ?? 'N/A' }}</td>
                            <td>{{ $student->classYear->class->name ?? 'N/A' }}</td>
                            <td>{{ $student->classYear->academicYear->name ?? 'N/A' }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->enrolled_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $student->is_active ? 'success' : 'secondary' }}">
                                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-people display-4 d-block mb-2"></i>
                                    No students found matching your criteria.
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
@endsection