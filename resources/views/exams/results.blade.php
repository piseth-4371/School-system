@extends('layouts.app')

@section('title', 'Enter Exam Results')
@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <h5>Form Errors:</h5>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Enter Exam Results - {{ $exam->name }}</h1>
                <div class="btn-group">
                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Exam
                    </a>
                </div>
            </div>
            <p class="text-muted">
                {{ $exam->subject->name }} - {{ $exam->classYear->class->name }} - 
                {{ $exam->classYear->academicYear->name ?? 'N/A' }} ({{ $exam->classYear->semester }})
            </p>
            <div class="alert alert-info">
                <strong>Exam Details:</strong> 
                Total Marks: {{ $exam->total_marks }} | 
                Passing Marks: {{ $exam->passing_marks }} | 
                Date: {{ $exam->exam_date->format('M d, Y') }} |
                Department: {{ $exam->classYear->department->name ?? 'N/A' }}
            </div>

            {{-- Debug information --}}
            <div class="alert alert-warning">
                <strong>Debug Info:</strong><br>
                Department ID: {{ $exam->classYear->department_id ?? 'N/A' }}<br>
                Department Name: {{ $exam->classYear->department->name ?? 'N/A' }}<br>
                Students in this department: {{ $students->count() }}
            </div>
        </div>
    </div>

    @if($students->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>No students found in the "{{ $exam->classYear->department->name ?? 'selected' }}" department.</strong>
            
            <div class="mt-3">
                <p>Possible reasons:</p>
                <ul>
                    <li>No students are enrolled in this department</li>
                    <li>The department assignment might be incorrect</li>
                    <li>Students might not be properly linked to departments</li>
                </ul>
            </div>

            <div class="mt-3">
                <a href="{{ route('students.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> Add New Student
                </a>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-building"></i> Manage Departments
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('exams.results.store', $exam) }}">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Score (0-{{ $exam->total_marks }})</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                @php
                                    $existingResult = $student->examDetails->first();
                                    $score = $existingResult->score ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->student_code }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td style="width: 150px;">
                                        <input type="number" 
                                               class="form-control score-input" 
                                               name="results[{{ $student->id }}][score]"
                                               value="{{ $score }}"
                                               min="0" 
                                               max="{{ $exam->total_marks }}"
                                               step="0.01"
                                               onchange="calculateGrade(this, {{ $exam->total_marks }}, {{ $exam->passing_marks }})">
                                        <input type="hidden" name="results[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                    </td>
                                    <td>
                                        <span class="grade-display badge bg-info">
                                            {{ $existingResult->grade ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-display">
                                            @if($existingResult)
                                                @if($existingResult->score >= $exam->passing_marks)
                                                    <span class="badge bg-success">Passed</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Not Graded</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control" 
                                               name="results[{{ $student->id }}][remarks]"
                                               value="{{ $existingResult->remarks ?? '' }}"
                                               placeholder="Optional remarks">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_calculate" checked>
                                    <label class="form-check-label" for="auto_calculate">
                                        Auto-calculate grades
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8 text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Save All Results
                                </button>
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection