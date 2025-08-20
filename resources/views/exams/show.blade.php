@extends('layouts.app')

@section('title', 'Exam Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Exam Details</h1>
                <div class="btn-group">
                    <a href="{{ route('exams.results.form', $exam) }}" class="btn btn-primary">
                        <i class="bi bi-clipboard-data"></i> Enter Results
                    </a>
                    <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Exam Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Exam Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Exam Name:</strong><br>{{ $exam->name }}</p>
                            <p><strong>Subject:</strong><br>{{ $exam->subject->name }}</p>
                            <p><strong>Class:</strong><br>
                                {{ $exam->classYear->class->name }} - {{ $exam->classYear->academicYear->name }}
                            </p>
                            <p><strong>Type:</strong><br>
                                <span class="badge bg-info text-dark">{{ ucfirst($exam->type) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date:</strong><br>{{ $exam->exam_date->format('M d, Y') }}</p>
                            <p><strong>Time:</strong><br>
                                {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}
                            </p>
                            <p><strong>Duration:</strong><br>{{ $exam->duration }} hours</p>
                            <p><strong>Classroom:</strong><br>{{ $exam->classroom->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Total Marks</h6>
                                    <h3 class="text-primary">{{ $exam->total_marks }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Passing Marks</h6>
                                    <h3 class="text-success">{{ $exam->passing_marks }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($exam->description)
                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mb-0">{{ $exam->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Results Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $results = $exam->examDetails;
                        $totalStudents = $students->count();
                        $studentsWithResults = $results->count();
                        $passed = $results->filter(fn($r) => $r->isPassed)->count();
                        $failed = $studentsWithResults - $passed;
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1">Total Students</h6>
                                <h4 class="text-primary mb-0">{{ $totalStudents }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1">Results Entered</h6>
                                <h4 class="text-info mb-0">{{ $studentsWithResults }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-1">Pending</h6>
                                <h4 class="text-warning mb-0">{{ $totalStudents - $studentsWithResults }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="border rounded p-2 bg-success bg-opacity-10">
                                <h6 class="mb-1">Passed</h6>
                                <h4 class="text-success mb-0">{{ $passed }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 bg-danger bg-opacity-10">
                                <h6 class="mb-1">Failed</h6>
                                <h4 class="text-danger mb-0">{{ $failed }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    @if($studentsWithResults > 0)
                    <div class="mt-3">
                        <strong>Average Score:</strong>
                        <div class="progress mt-1" style="height: 20px;">
                            @php
                                $averagePercentage = ($results->avg('score') / $exam->total_marks) * 100;
                            @endphp
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $averagePercentage }}%;"
                                 aria-valuenow="{{ $averagePercentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($results->avg('score'), 1) }} / {{ $exam->total_marks }}
                                ({{ number_format($averagePercentage, 1) }}%)
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('exams.results.form', $exam) }}" class="btn btn-primary">
                            <i class="bi bi-clipboard-data me-2"></i>Enter/Update Results
                        </a>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Download Results Sheet
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-2"></i>Print Exam Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Results Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Results</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                @php
                                    $result = $exam->examDetails->firstWhere('student_id', $student->id);
                                @endphp
                                <tr>
                                    <td>{{ $student->student_code }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>
                                        @if($result)
                                            <strong>{{ $result->score }}</strong> / {{ $exam->total_marks }}
                                        @else
                                            <span class="text-muted">Not entered</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result)
                                            {{ number_format($result->percentage, 1) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($result)
                                            <span class="badge bg-secondary">{{ $result->grade }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($result)
                                            @if($result->isPassed)
                                                <span class="badge bg-success">Passed</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result && $result->remarks)
                                            {{ $result->remarks }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection