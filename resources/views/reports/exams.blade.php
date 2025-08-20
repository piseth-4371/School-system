@extends('layouts.app')

@section('title', 'Exam Reports')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Exam Reports</h1>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.exams') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Exam Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="midterm" {{ request('type') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Final</option>
                        <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    </select>
                </div>
                <div class="col-md-2">
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
            <h5 class="card-title mb-0">Exam Reports</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Total Marks</th>
                            <th>Passing Marks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                        <tr>
                            <td><strong>{{ $exam->name }}</strong></td>
                            <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                            <td>
                                {{ $exam->classYear->class->name ?? 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $exam->classYear->academicYear->name ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($exam->type) }}</span>
                            </td>
                            <td>{{ $exam->total_marks }}</td>
                            <td>{{ $exam->passing_marks }}</td>
                            <td>
                                @if($exam->exam_date > now())
                                    <span class="badge bg-warning">Upcoming</span>
                                @else
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-clipboard-x display-4 d-block mb-2"></i>
                                    No exams found matching your criteria.
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