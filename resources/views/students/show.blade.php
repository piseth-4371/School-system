@extends('layouts.app')

@section('title', 'Student Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Student Details</h1>
                <div class="btn-group">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h4 class="mt-3">{{ $student->user->name ?? 'N/A' }}</h4>
                    <p class="text-muted">{{ $student->student_code }}</p>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Quick Stats</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Payments:</span>
                        <strong>${{ number_format($student->payments->sum('paid_amount'), 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Attendance Rate:</span>
                        <strong>95%</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Exams Taken:</span>
                        <strong>{{ $student->examDetails ? $student->examDetails->count() : 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $student->user->email ?? 'N/A' }}</p>
                            <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                            <p><strong>Date of Birth:</strong> {{ $student->dob ? $student->dob->format('M d, Y') : 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $student->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Department:</strong> {{ $student->department->name ?? 'N/A' }}</p>
                            <p><strong>Enrolled Date:</strong> {{ $student->enrolled_date ? $student->enrolled_date->format('M d, Y') : 'N/A' }}</p>
                            <!-- REMOVED class year and academic year since they don't exist -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Parent/Guardian Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Parent Name:</strong> {{ $student->parent_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Parent Phone:</strong> {{ $student->parent_phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Address</h5>
                    <p>{{ $student->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Payments</h5>
                </div>
                <div class="card-body">
                    @if($student->payments && $student->payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->payments->take(5) as $payment)
                                <tr>
                                    <td>{{ $payment->invoice_number }}</td>
                                    <td>${{ number_format($payment->paid_amount, 2) }}</td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No payment records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Exam Results -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Exam Results</h5>
                </div>
                <div class="card-body">
                    @if($student->examDetails && $student->examDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->examDetails->take(5) as $result)
                                <tr>
                                    <td>{{ $result->exam->name ?? 'N/A' }}</td>
                                    <td>{{ $result->exam->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $result->score }}/{{ $result->exam->total_marks ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $result->grade }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $result->score >= ($result->exam->passing_marks ?? 0) ? 'success' : 'danger' }}">
                                            {{ $result->score >= ($result->exam->passing_marks ?? 0) ? 'Passed' : 'Failed' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No exam results found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection