@extends('layouts.app')

@section('title', 'System Settings')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">System Settings</h1>
            <p class="text-muted">Manage system configuration and academic settings</p>
        </div>
    </div>

    <div class="row">
        <!-- Academic Settings Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="bi bi-calendar-range display-4"></i>
                    </div>
                    <h5 class="card-title">Academic Years</h5>
                    <p class="card-text">Manage academic years and set current academic year</p>
                    <a href="{{ route('settings.academic-years') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- System Configuration Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i class="bi bi-building-gear display-4"></i>
                    </div>
                    <h5 class="card-title">System Configuration</h5>
                    <p class="card-text">School information, currency, and system settings</p>
                    <a href="{{ route('settings.system-config') }}" class="btn btn-success">
                        <i class="bi bi-sliders me-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="bi bi-mortarboard display-4"></i>
                    </div>
                    <h5 class="card-title">Class Management</h5>
                    <p class="card-text">Manage classes, departments, and class years</p>
                    <a href="{{ route('settings.class-management') }}" class="btn btn-info">
                        <i class="bi bi-diagram-3 me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Year Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="bi bi-calendar2-week display-4"></i>
                    </div>
                    <h5 class="card-title">Class Year Management</h5>
                    <p class="card-text">Manage class years and semester assignments</p>
                    <a href="{{ route('settings.class-year-management') }}" class="btn btn-warning">
                        <i class="bi bi-calendar-check me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <div class="text-secondary mb-3">
                        <i class="bi bi-people display-4"></i>
                    </div>
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Manage users, roles, and permissions</p>
                    <a href="#" class="btn btn-secondary">
                        <i class="bi bi-person-gear me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Backup & Restore Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-database display-4"></i>
                    </div>
                    <h5 class="card-title">Backup & Restore</h5>
                    <p class="card-text">System backup and data restoration</p>
                    <a href="#" class="btn btn-danger">
                        <i class="bi bi-cloud-arrow-down me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Current Academic Year</h6>
                                <h4 class="text-primary">
                                    @php
                                        $currentYear = \App\Models\AcademicYear::where('is_current', true)->first();
                                    @endphp
                                    {{ $currentYear ? $currentYear->name : 'Not Set' }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Active Classes</h6>
                                <h4 class="text-success">{{ \App\Models\SchoolClass::where('is_active', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Active Departments</h6>
                                <h4 class="text-info">{{ \App\Models\Department::where('is_active', true)->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Class Years</h6>
                                <h4 class="text-warning">{{ \App\Models\ClassYear::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection