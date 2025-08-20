@extends('layouts.app')

@section('title', 'Create New Exam')
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
            <h1 class="h3 mb-0">Create New Exam</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('exams.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Exam Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required autofocus>
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="class_year_id" class="form-label">Class Year *</label>
                            <select class="form-select" id="class_year_id" name="class_year_id" required>
                                <option value="">Select Class Year</option>
                                @foreach($classYears as $classYear)
                                    <option value="{{ $classYear->id }}" {{ old('class_year_id') == $classYear->id ? 'selected' : '' }}>
                                        {{ $classYear->class->name ?? 'N/A' }} - {{ $classYear->academicYear->name ?? 'N/A' }} ({{ $classYear->semester }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_year_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject *</label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <!-- ADD TEACHER SELECTION FIELD -->
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Teacher *</label>
                            <select class="form-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name ?? 'N/A' }} ({{ $teacher->teacher_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Exam Type *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                @foreach($examTypes as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exam_date" class="form-label">Exam Date *</label>
                                    <input type="date" class="form-control" id="exam_date" name="exam_date" 
                                           value="{{ old('exam_date') }}" required>
                                    @error('exam_date') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="classroom_id" class="form-label">Classroom *</label>
                                    <select class="form-select" id="classroom_id" name="classroom_id" required>
                                        <option value="">Select Classroom</option>
                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }} (Capacity: {{ $classroom->capacity }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('classroom_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time *</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" 
                                           value="{{ old('start_time') }}" required>
                                    @error('start_time') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time *</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" 
                                           value="{{ old('end_time') }}" required>
                                    @error('end_time') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_marks" class="form-label">Total Marks *</label>
                                    <input type="number" class="form-control" id="total_marks" name="total_marks" 
                                           min="1" value="{{ old('total_marks', 100) }}" required>
                                    @error('total_marks') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="passing_marks" class="form-label">Passing Marks *</label>
                                    <input type="number" class="form-control" id="passing_marks" name="passing_marks" 
                                           min="0" value="{{ old('passing_marks', 40) }}" required>
                                    @error('passing_marks') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Exam</button>
                    <a href="{{ route('exams.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validate that end time is after start time
    document.getElementById('end_time').addEventListener('change', function() {
        const startTime = document.getElementById('start_time').value;
        const endTime = this.value;
        
        if (startTime && endTime && endTime <= startTime) {
            alert('End time must be after start time');
            this.value = '';
        }
    });

    // Validate that passing marks are less than total marks
    document.getElementById('passing_marks').addEventListener('change', function() {
        const totalMarks = parseInt(document.getElementById('total_marks').value);
        const passingMarks = parseInt(this.value);
        
        if (passingMarks > totalMarks) {
            alert('Passing marks cannot be greater than total marks');
            this.value = totalMarks;
        }
    });
</script>
@endpush