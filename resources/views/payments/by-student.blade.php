@extends('layouts.app')

@section('title', 'Payments for Student')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Payments for {{ $student->user->name }}</h1>
                <div class="btn-group">
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="bi bi-cash-coin"></i> New Payment
                    </a>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> All Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Student Code:</strong> {{ $student->student_code }}
                </div>
                <div class="col-md-3">
                    <strong>Department:</strong> {{ $student->department->name }}
                </div>
                <div class="col-md-3">
                    <strong>Class:</strong> {{ $student->classYear->class->name }}
                </div>
                <div class="col-md-3">
                    <strong>Total Payments:</strong> ${{ number_format($payments->sum('paid_amount'), 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->invoice_number }}</strong></td>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>
                                ${{ number_format($payment->amount, 2) }}
                                @if($payment->discount > 0)
                                <br>
                                <small class="text-success">- ${{ number_format($payment->discount, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>${{ number_format($payment->paid_amount, 2) }}</strong>
                                @if($payment->remainingAmount > 0)
                                <br>
                                <small class="text-danger">${{ number_format($payment->remainingAmount, 2) }} left</small>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $payment->due_date < now() && $payment->status != 'paid' ? 'text-danger' : '' }}">
                                    {{ $payment->due_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-cash-coin display-4 d-block mb-2"></i>
                                    No payments found for this student.
                                    <p class="mt-2">
                                        <a href="{{ route('payments.create') }}?student_id={{ $student->id }}" class="btn btn-primary btn-sm">
                                            Record a payment for this student
                                        </a>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                </div>
                <nav>
                    {{ $payments->links() }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection