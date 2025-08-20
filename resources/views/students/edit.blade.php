@extends('layouts.app')

@section('title', 'Edit Student')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Edit Student</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-3">Login Information</h4>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $student->user->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $student->user->email) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="mb-3">Student Information</h4>
                        <div class="mb-3">
                            <label for="student_code" class="form-label">Student Code *</label>
                            <input type="text" class="form-control" id="student_code" name="student_code" 
                                   value="{{ old('student_code', $student->student_code) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control" id="dob" name="dob" 
                                   value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone', $student->phone) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_name" class="form-label">Parent Name *</label>
                            <input type="text" class="form-control" id="parent_name" name="parent_name" 
                                   value="{{ old('parent_name', $student->parent_name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_phone" class="form-label">Parent Phone *</label>
                            <input type="text" class="form-control" id="parent_phone" name="parent_phone" 
                                   value="{{ old('parent_phone', $student->parent_phone) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="enrolled_date" class="form-label">Enrolled Date *</label>
                            <input type="date" class="form-control" id="enrolled_date" name="enrolled_date" 
                                   value="{{ old('enrolled_date', $student->enrolled_date ? $student->enrolled_date->format('Y-m-d') : '') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department *</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- REMOVE THIS SECTION - class_year_id doesn't exist in database --}}
                        {{--
                        <div class="mb-3">
                            <label for="class_year_id" class="form-label">Class Year *</label>
                            <select name="class_year_id" class="form-control" required>
                                <option value="">Select Class Year</option>
                                @foreach($classYears as $classYear)
                                    <option value="{{ $classYear->id }}" {{ old('class_year_id', $student->class_year_id) == $classYear->id ? 'selected' : '' }}>
                                        {{ $classYear->class->name ?? 'N/A' }} - {{ $classYear->academicYear->name ?? 'N/A' }} ({{ $classYear->semester }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        --}}
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $student->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection