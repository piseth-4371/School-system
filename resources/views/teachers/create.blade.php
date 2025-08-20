@extends('layouts.app')

@section('title', 'Add New Teacher')
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
            <h1 class="h3 mb-0">Add New Teacher</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.store') }}">
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
                        <h4 class="mb-3">Teacher Information</h4>
                        <div class="mb-3">
                            <label for="teacher_code" class="form-label">Teacher Code *</label>
                            <input type="text" class="form-control" id="teacher_code" name="teacher_code" 
                                   value="{{ old('teacher_code') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="qualification" class="form-label">Qualification *</label>
                            <input type="text" class="form-control" id="qualification" name="qualification" 
                                   value="{{ old('qualification') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization *</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" 
                                   value="{{ old('specialization') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="joined_date" class="form-label">Joined Date *</label>
                            <input type="date" class="form-control" id="joined_date" name="joined_date" 
                                   value="{{ old('joined_date') }}" required>
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
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Teacher</button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection