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

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Current Academic Year</h6>
                            @php
                                $currentYear = \App\Models\AcademicYear::where('is_current', true)->first();
                            @endphp
                            <h3 class="card-text">{{ $currentYear ? $currentYear->name : 'Not Set' }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-range display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Active Classes</h6>
                            <h3 class="card-text">{{ \App\Models\SchoolClass::where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-mortarboard display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Active Departments</h6>
                            <h3 class="card-text">{{ \App\Models\Department::where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-building display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title">Class Years</h6>
                            <h3 class="card-text">{{ \App\Models\ClassYear::count() }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar2-week display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Cards Grid -->
    <div class="row">
        <!-- Academic Settings Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="bi bi-calendar-range display-4"></i>
                    </div>
                    <h5 class="card-title">Academic Years</h5>
                    <p class="card-text">Manage academic years and set current academic year</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.academic-years') }}" class="btn btn-primary w-100">
                        <i class="bi bi-gear me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- System Configuration Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i class="bi bi-building-gear display-4"></i>
                    </div>
                    <h5 class="card-title">System Configuration</h5>
                    <p class="card-text">School information, currency, and system settings</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.system-config') }}" class="btn btn-success w-100">
                        <i class="bi bi-sliders me-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="bi bi-mortarboard display-4"></i>
                    </div>
                    <h5 class="card-title">Class Management</h5>
                    <p class="card-text">Manage classes, departments, and class years</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.class-management') }}" class="btn btn-info w-100">
                        <i class="bi bi-diagram-3 me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Year Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="bi bi-calendar2-week display-4"></i>
                    </div>
                    <h5 class="card-title">Class Year Management</h5>
                    <p class="card-text">Manage class years and semester assignments</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.class-year-management') }}" class="btn btn-warning w-100">
                        <i class="bi bi-calendar-check me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-secondary mb-3">
                        <i class="bi bi-people display-4"></i>
                    </div>
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Manage users, roles, and permissions</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.user-management.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-person-gear me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Backup & Restore Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-database display-4"></i>
                    </div>
                    <h5 class="card-title">Backup & Restore</h5>
                    <p class="card-text">System backup and data restoration</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('settings.backup.index') }}" class="btn btn-danger w-100">
                        <i class="bi bi-cloud-arrow-down me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent System Activity</h5>
                    <small class="text-muted">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>User</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example activity items - you would replace with real data -->
                                <tr>
                                    <td><span class="badge bg-success">Created</span></td>
                                    <td>New academic year added</td>
                                    <td>System Admin</td>
                                    <td>{{ now()->subHours(2)->format('M d, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Updated</span></td>
                                    <td>System configuration modified</td>
                                    <td>Administrator</td>
                                    <td>{{ now()->subDays(1)->format('M d, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning">Backup</span></td>
                                    <td>System backup completed</td>
                                    <td>Automated System</td>
                                    <td>{{ now()->subDays(3)->format('M d, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.2s;
        border: none;
        border-radius: 10px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection