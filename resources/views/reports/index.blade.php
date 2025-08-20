@extends('layouts.app')

@section('title', 'Reports Dashboard')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Reports Dashboard</h1>
            <p class="text-muted">Generate and view various system reports</p>
        </div>
    </div>

    <div class="row">
        <!-- Student Reports Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="bi bi-people-fill display-4"></i>
                    </div>
                    <h5 class="card-title">Student Reports</h5>
                    <p class="card-text">Generate student lists, demographics, and enrollment reports</p>
                    <a href="{{ route('reports.students') }}" class="btn btn-primary">
                        <i class="bi bi-bar-chart me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Financial Reports Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i class="bi bi-cash-coin display-4"></i>
                    </div>
                    <h5 class="card-title">Financial Reports</h5>
                    <p class="card-text">Payment reports, revenue analysis, and financial summaries</p>
                    <div class="btn-group">
                        <a href="{{ route('reports.payments') }}" class="btn btn-success">
                            <i class="bi bi-receipt me-2"></i>Payments
                        </a>
                        <a href="{{ route('reports.financial-summary') }}" class="btn btn-outline-success">
                            Summary
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Reports Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="bi bi-calendar-check display-4"></i>
                    </div>
                    <h5 class="card-title">Attendance Reports</h5>
                    <p class="card-text">Class attendance records and attendance statistics</p>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-info">
                        <i class="bi bi-clipboard-data me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Exam Reports Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="bi bi-clipboard-data display-4"></i>
                    </div>
                    <h5 class="card-title">Exam Reports</h5>
                    <p class="card-text">Exam schedules, results, and performance analysis</p>
                    <a href="{{ route('reports.exams') }}" class="btn btn-warning">
                        <i class="bi bi-graph-up me-2"></i>View Reports
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
                    <h5 class="card-title mb-0">Quick Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Total Students</h6>
                                <h3 class="text-primary">{{ App\Models\Student::count() }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Total Payments</h6>
                                <h3 class="text-success">${{ number_format(App\Models\Payment::sum('paid_amount'), 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Today's Attendance</h6>
                                <h3 class="text-info">{{ App\Models\Attendance::whereDate('date', today())->count() }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">Upcoming Exams</h6>
                                <h3 class="text-warning">{{ App\Models\Exam::where('exam_date', '>=', today())->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection