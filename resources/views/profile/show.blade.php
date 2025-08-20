@extends('layouts.app')

@section('title', 'My Profile')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">My Profile</h1>
                <div class="btn-group">
                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/profile-photos/' . $user->profile_photo) }}" 
                                 class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; font-size: 3rem;">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ ucfirst($user->role) }}</p>
                    
                    <div class="badge bg-info text-dark mb-3">
                        <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                    </div>

                    @if($profileData && $profileData->phone)
                    <div class="badge bg-secondary mb-3">
                        <i class="bi bi-phone me-1"></i> {{ $profileData->phone }}
                    </div>
                    @endif

                    <div class="mt-3">
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i> Active
                        </span>
                        <span class="badge bg-primary">
                            Member since {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            @if($user->role === 'student' && $profileData)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Student Information</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Student Code:</span>
                        <strong>{{ $profileData->student_code }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Department:</span>
                        <strong>{{ $profileData->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Class:</span>
                        <strong>{{ $profileData->classYear->class->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Academic Year:</span>
                        <strong>{{ $profileData->classYear->academicYear->name ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
            @endif

            @if($user->role === 'teacher' && $profileData)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Teacher Information</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Teacher Code:</span>
                        <strong>{{ $profileData->teacher_code }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Department:</span>
                        <strong>{{ $profileData->department->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Qualification:</span>
                        <strong>{{ $profileData->qualification }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Specialization:</span>
                        <strong>{{ $profileData->specialization }}</strong>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Personal Information</h6>
                            <p><strong>Full Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                            
                            @if($profileData && $profileData->phone)
                            <p><strong>Phone:</strong> {{ $profileData->phone }}</p>
                            @endif
                            
                            @if($profileData && $profileData->address)
                            <p><strong>Address:</strong> {{ $profileData->address }}</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Account Information</h6>
                            <p><strong>Account Status:</strong> 
                                <span class="badge bg-success">Active</span>
                            </p>
                            <p><strong>Email Verified:</strong> 
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Not Verified</span>
                                @endif
                            </p>
                            <p><strong>Member Since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Additional Information based on role -->
                    @if($user->role === 'student' && $profileData)
                    <hr>
                    <h6>Academic Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> 
                                {{ $profileData->dob ? $profileData->dob->format('F d, Y') : 'N/A' }}
                            </p>
                            <p><strong>Gender:</strong> {{ ucfirst($profileData->gender) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Enrollment Date:</strong> 
                                {{ $profileData->enrolled_date->format('F d, Y') }}
                            </p>
                            <p><strong>Parent Name:</strong> {{ $profileData->parent_name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($user->role === 'teacher' && $profileData)
                    <hr>
                    <h6>Professional Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Joined Date:</strong> 
                                {{ $profileData->joined_date->format('F d, Y') }}
                            </p>
                            <p><strong>Gender:</strong> {{ ucfirst($profileData->gender) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Qualification:</strong> {{ $profileData->qualification }}</p>
                            <p><strong>Specialization:</strong> {{ $profileData->specialization }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Profile Viewed</h6>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded-circle p-2 me-3">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Last Login</h6>
                                    <small class="text-muted">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning text-dark rounded-circle p-2 me-3">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Account Created</h6>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection