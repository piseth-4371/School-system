@extends('layouts.app')

@section('title', 'Exams Management')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Exams Management</h1>
                <a href="{{ route('exams.create') }}" class="btn btn-primary">
                    <i class="bi bi-clipboard-plus"></i> Create Exam
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('exams.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Exam Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        @foreach($examTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="class_year" class="form-label">Class Year</label>
                    <select class="form-select" id="class_year" name="class_year">
                        <option value="">All Classes</option>
                        @foreach($classYears as $classYear)
                            <option value="{{ $classYear->id }}" {{ request('class_year') == $classYear->id ? 'selected' : '' }}>
                                {{ $classYear->class->name ?? 'N/A' }} - {{ $classYear->academicYear->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Search exams..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Marks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                        <tr>
                            <td>
                                <strong>{{ $exam->name }}</strong>
                                @if($exam->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $exam->subject->name }}</td>
                            <td>
                                {{ $exam->classYear->class->name }}
                                <br>
                                <small class="text-muted">{{ $exam->classYear->academicYear->name }}</small>
                            </td>
                            <td>
                                {{ $exam->exam_date->format('M d, Y') }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($exam->type) }}
                                </span>
                            </td>
                            <td>
                                {{ $exam->total_marks }} total
                                <br>
                                <small class="text-muted">{{ $exam->passing_marks }} to pass</small>
                            </td>
                            <td>
                                @if($exam->isUpcoming)
                                    <span class="badge bg-warning">Upcoming</span>
                                @else
                                    <span class="badge bg-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-info btn-sm"
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning btn-sm"
                                       data-bs-toggle="tooltip" title="Edit Exam">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('exams.results.form', $exam) }}" class="btn btn-primary btn-sm"
                                       data-bs-toggle="tooltip" title="Enter Results">
                                        <i class="bi bi-clipboard-data"></i>
                                    </a>
                                    <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this exam?')"
                                                data-bs-toggle="tooltip" title="Delete Exam">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-clipboard-x display-4 d-block mb-2"></i>
                                    No exams found.
                                    @if(request()->anyFilled(['type', 'class_year', 'status', 'search']))
                                        <p class="mt-2">Try adjusting your search filters.</p>
                                    @else
                                        <p class="mt-2">
                                            <a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm">
                                                Create your first exam
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($exams->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $exams->firstItem() }} to {{ $exams->lastItem() }} of {{ $exams->total() }} entries
                </div>
                <nav>
                    {{ $exams->links() }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Auto-submit filters when changed
    document.getElementById('type').addEventListener('change', function() {
        this.form.submit()
    })
    
    document.getElementById('class_year').addEventListener('change', function() {
        this.form.submit()
    })
    
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit()
    })
</script>
@endpush