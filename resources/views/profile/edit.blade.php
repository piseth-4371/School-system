@extends('layouts.app')

@section('title', 'Edit Profile')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Edit Profile</h1>
                <div class="btn-group">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Profile Photo -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/profile-photos/' . $user->profile_photo) }}" 
                                     class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                                <br>
                                <form method="POST" action="{{ route('profile.delete-photo') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to remove your profile photo?')">
                                        <i class="bi bi-trash me-1"></i>Remove Photo
                                    </button>
                                </form>
                            @else
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px; font-size: 3rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Upload Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="form-text">Max size: 2MB. Supported formats: JPG, PNG, GIF</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone', $profileData->phone ?? '') }}">
                                </div>
                            </div>
                            @if($user->role === 'student')
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $profileData->address ?? '') }}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Leave password fields blank if you don't want to change your password.
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Update Profile
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview profile photo before upload
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'rounded-circle mb-3';
                preview.style.width = '150px';
                preview.style.height = '150px';
                preview.style.objectFit = 'cover';
                
                const container = document.querySelector('.card-body.text-center');
                const oldImage = container.querySelector('img, div');
                if (oldImage) {
                    container.replaceChild(preview, oldImage);
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush