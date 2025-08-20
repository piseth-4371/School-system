@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Dashboard Overview</h1>
        <p class="text-muted" id="welcome-message">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="text-muted">Total Students</h5>
                    <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-primary text-white p-3 rounded-circle">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="text-muted">Total Teachers</h5>
                    <h2 class="mb-0">{{ $stats['total_teachers'] }}</h2>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-success text-white p-3 rounded-circle">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="text-muted">Departments</h5>
                    <h2 class="mb-0">{{ $stats['total_departments'] }}</h2>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-info text-white p-3 rounded-circle">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="text-muted">Revenue</h5>
                    <h2 class="mb-0">${{ number_format($stats['active_payments'], 2) }}</h2>
                </div>
                <div class="flex-shrink-0">
                    <div class="bg-warning text-white p-3 rounded-circle">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Students -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Students</h5>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Joined</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentStudents as $student)
                            <tr>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->student_code }}</td>
                                <td>{{ $student->enrolled_date->format('M d, Y') }}</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Payments</h5>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->student->user->name }}</td>
                                <td>${{ number_format($payment->paid_amount, 2) }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Upcoming Exams -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Exams</h5>
            </div>
            <div class="card-body">
                @foreach($upcomingExams as $exam)
                <div class="d-flex align-items-center mb-3 p-2 border rounded">
                    <div class="flex-shrink-0 bg-primary text-white p-3 rounded-circle me-3">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $exam->name }}</h6>
                        <p class="text-muted mb-0">
                            {{ $exam->subject->name }} - {{ $exam->exam_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('students.create') }}" class="btn btn-primary w-100 h-100 py-3">
                            <i class="bi bi-person-plus fs-4 d-block mb-2"></i>
                            Add Student
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('payments.create') }}" class="btn btn-success w-100 h-100 py-3">
                            <i class="bi bi-cash-coin fs-4 d-block mb-2"></i>
                            Record Payment
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('attendances.create') }}" class="btn btn-info w-100 h-100 py-3">
                            <i class="bi bi-calendar-check fs-4 d-block mb-2"></i>
                            Take Attendance
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-warning w-100 h-100 py-3">
                            <i class="bi bi-clipboard-plus fs-4 d-block mb-2"></i>
                            Create Exam
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart initialization can go here
    console.log('Dashboard loaded');
</script>
@endpush

<script>
    setTimeout(function() {
        let message = document.getElementById('welcome-message');

        // Start the animation by adding a class
        message.classList.add('fade-out-slide-up');
    }, 5000);  // Show the animation after 5 seconds
</script>