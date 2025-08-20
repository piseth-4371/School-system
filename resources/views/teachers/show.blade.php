@extends('layouts.app')

@section('title', 'Teacher Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Teacher Details</h1>
                <div class="btn-group">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Teacher Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h4 class="mt-3">{{ $teacher->user->name ?? 'N/A' }}</h4>
                    <p class="text-muted">{{ $teacher->teacher_code ?? 'N/A' }}</p>
                    <span class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }}">
                        {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Quick Stats</h6>
                    <!-- Quick Stats Section -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Classes:</span>
                        <strong>{{ $teacher->classes ? $teacher->classes->count() : 0 }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Students:</span>
                        <strong>{{ $teacher->students ? $teacher->students->count() : 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $teacher->user->email ?? 'N/A' }}</p>
                            <p><strong>Gender:</strong> {{ ucfirst($teacher->gender) }}</p>
                            <p><strong>Department:</strong> {{ $teacher->department->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Qualification:</strong> {{ $teacher->qualification ?? 'N/A' }}</p>
                            <p><strong>Specialization:</strong> {{ $teacher->specialization ?? 'N/A' }}</p>
                            <p><strong>Joined Date:</strong> {{ $teacher->joined_date ? $teacher->joined_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Additional Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $teacher->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Classes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Classes</h5>
                </div>
                <div class="card-body">
                    @if($teacher->classes->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Department</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacher->classes->take(5) as $class)
                                <tr>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->department->name ?? 'N/A' }}</td>
                                    <td>{{ $class->start_date->format('M d, Y') }}</td>
                                    <td>{{ $class->end_date->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No class records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
