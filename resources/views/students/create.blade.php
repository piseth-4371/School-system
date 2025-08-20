@extends('layouts.app')

@section('title', 'Create New Student')
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
            <h1 class="h3 mb-0">Create New Student</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('students.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-3">Login Information</h4>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="mb-3">Student Information</h4>

                        <div class="mb-3">
                            <label for="student_code" class="form-label">Student Code *</label>
                            <input type="text" class="form-control" id="student_code" name="student_code" 
                                   value="{{ old('student_code') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control" id="dob" name="dob" 
                                   value="{{ old('dob') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department *</label>
                            <select name="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="class_year_id" class="form-label">Class Year *</label>
                            <select name="class_year_id" class="form-control" required>
                                <option value="">Select Class Year</option>
                                @foreach($classYears as $classYear)
                                    <option value="{{ $classYear['id'] }}" {{ old('class_year_id') == $classYear['id'] ? 'selected' : '' }}>
                                        {{ $classYear['display_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Add the missing fields here -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ old('address') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_name" class="form-label">Parent Name *</label>
                            <input type="text" class="form-control" id="parent_name" name="parent_name" 
                                   value="{{ old('parent_name') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_phone" class="form-label">Parent Phone *</label>
                            <input type="text" class="form-control" id="parent_phone" name="parent_phone" 
                                   value="{{ old('parent_phone') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="enrolled_date" class="form-label">Enrolled Date *</label>
                            <input type="date" class="form-control" id="enrolled_date" name="enrolled_date" 
                                   value="{{ old('enrolled_date') }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
