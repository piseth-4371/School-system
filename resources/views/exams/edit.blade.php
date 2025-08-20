@extends('layouts.app')

@section('title', 'Edit Exam')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Edit Exam</h1>
            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Fix the issues below.</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('exams.update', $exam) }}" method="POST" class="row g-3">
                @csrf @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Exam Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $exam->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Subject *</label>
                    <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id', $exam->subject_id)==$subject->id)>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Class Year *</label>
                    <select name="class_year_id" class="form-select @error('class_year_id') is-invalid @enderror" required>
                        <option value="">Select Class Year</option>
                        @foreach($classYears as $classYear)
                            <option value="{{ $classYear->id }}" @selected(old('class_year_id', $exam->class_year_id)==$classYear->id)>
                                {{ $classYear->class->name ?? 'N/A' }} - {{ $classYear->academicYear->name ?? 'N/A' }} ({{ $classYear->semester }})
                            </option>
                        @endforeach
                    </select>
                    @error('class_year_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Teacher *</label>
                    <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $exam->teacher_id)==$teacher->id)>
                                {{ $teacher->user->name ?? 'N/A' }} ({{ $teacher->teacher_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Classroom *</label>
                    <select name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                        <option value="">Select Classroom</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id', $exam->classroom_id)==$classroom->id)>
                                {{ $classroom->name }} (Capacity: {{ $classroom->capacity }})
                            </option>
                        @endforeach
                    </select>
                    @error('classroom_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Exam Type *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        @foreach($examTypes as $type)
                            <option value="{{ $type }}" @selected(old('type', $exam->type)==$type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Exam Date *</label>
                    <input type="date" name="exam_date" value="{{ old('exam_date', $exam->exam_date ? $exam->exam_date->format('Y-m-d') : '') }}"
                           class="form-control @error('exam_date') is-invalid @enderror" required>
                    @error('exam_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Start Time *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $exam->start_time) }}"
                           class="form-control @error('start_time') is-invalid @enderror" required>
                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">End Time *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $exam->end_time) }}"
                           class="form-control @error('end_time') is-invalid @enderror" required>
                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Total Marks *</label>
                    <input type="number" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}"
                           class="form-control @error('total_marks') is-invalid @enderror" min="1" required>
                    @error('total_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Passing Marks *</label>
                    <input type="number" name="passing_marks" value="{{ old('passing_marks', $exam->passing_marks) }}"
                           class="form-control @error('passing_marks') is-invalid @enderror" min="0" required>
                    @error('passing_marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3" placeholder="Optional exam description">{{ old('description', $exam->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Update Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validate that end time is after start time
    document.querySelector('input[name="end_time"]').addEventListener('change', function() {
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = this.value;
        
        if (startTime && endTime && endTime <= startTime) {
            alert('End time must be after start time');
            this.value = '';
        }
    });

    // Validate that passing marks are less than total marks
    document.querySelector('input[name="passing_marks"]').addEventListener('change', function() {
        const totalMarks = parseInt(document.querySelector('input[name="total_marks"]').value);
        const passingMarks = parseInt(this.value);
        
        if (passingMarks > totalMarks) {
            alert('Passing marks cannot be greater than total marks');
            this.value = totalMarks;
        }
    });
</script>
@endpush