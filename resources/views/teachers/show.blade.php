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
                    <p class="text-muted">{{ $teacher->teacher_code }}</p>
                    <span class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }}">
                        {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Quick Stats</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Classes:</span>
                        <strong>{{ $teacher->classes ? $teacher->classes->count() : 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Exams Created:</span>
                        <strong>{{ $teacher->exams ? $teacher->exams->count() : 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Timetable Slots:</span>
                        <strong>{{ $teacher->timetables ? $teacher->timetables->count() : 0 }}</strong>
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
                            <p><strong>Qualification:</strong> {{ $teacher->qualification }}</p>
                            <p><strong>Specialization:</strong> {{ $teacher->specialization }}</p>
                            <p><strong>Joined Date:</strong> {{ $teacher->joined_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information (if fields exist) -->
            @if($teacher->phone || $teacher->address)
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Contact Information</h5>
                    <div class="row">
                        @if($teacher->phone)
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> {{ $teacher->phone }}</p>
                        </div>
                        @endif
                        @if($teacher->address)
                        <div class="col-md-6">
                            <p><strong>Address:</strong> {{ $teacher->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Exams -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Exams</h5>
                </div>
                <div class="card-body">
                    @if($teacher->exams && $teacher->exams->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacher->exams->take(5) as $exam)
                                <tr>
                                    <td>{{ $exam->name }}</td>
                                    <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($exam->type) }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No exam records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Timetable -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timetable Schedule</h5>
                </div>
                <div class="card-body">
                    @if($teacher->timetables && $teacher->timetables->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacher->timetables->take(5) as $timetable)
                                <tr>
                                    <td>{{ $timetable->day->name ?? 'N/A' }}</td>
                                    <td>{{ $timetable->start_time }} - {{ $timetable->end_time }}</td>
                                    <td>{{ $timetable->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $timetable->classYear->class->name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No timetable records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection