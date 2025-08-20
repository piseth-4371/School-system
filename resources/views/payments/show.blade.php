@extends('layouts.app')

@section('title', 'Payment Details')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Payment Details</h1>
                <div class="btn-group">
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Invoice Number:</strong><br>{{ $payment->invoice_number }}</p>
                            <p><strong>Payment Date:</strong><br>{{ $payment->payment_date->format('M d, Y') }}</p>
                            <p><strong>Due Date:</strong><br>
                                <span class="{{ $payment->due_date < now() && $payment->status != 'paid' ? 'text-danger' : '' }}">
                                    {{ $payment->due_date->format('M d, Y') }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong><br>
                                @php
                                    $statusColors = [
                                        'paid' => 'success',
                                        'unpaid' => 'warning',
                                        'partial' => 'info',
                                        'overdue' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            <p><strong>Payment Method:</strong><br>
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </p>
                            <p><strong>Recorded By:</strong><br>{{ $payment->recorder->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Amount Details</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <strong>${{ number_format($payment->amount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <strong class="text-success">- ${{ number_format($payment->discount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Net Amount:</span>
                        <strong>${{ number_format($payment->amount - $payment->discount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Paid Amount:</span>
                        <strong class="text-primary">${{ number_format($payment->paid_amount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Remaining Amount:</span>
                        <strong class="{{ $payment->remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($payment->remainingAmount, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $payment->student->user->name }}</h6>
                            <p class="text-muted mb-0">{{ $payment->student->student_code }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Department:</strong><br>{{ $payment->student->department->name }}</p>
                            <p><strong>Class:</strong><br>{{ $payment->student->classYear->class->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong><br>{{ $payment->student->user->email }}</p>
                            <p><strong>Phone:</strong><br>{{ $payment->student->phone }}</p>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('payments.byStudent', $payment->student_id) }}" class="btn btn-outline-primary btn-sm">
                            View All Payments for This Student
                        </a>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($payment->description)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $payment->description }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection