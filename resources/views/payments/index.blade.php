@extends('layouts.app')

@section('title', 'Payments Management')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Payments Management</h1>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="bi bi-cash-coin"></i> Record Payment
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center p-3">
                    <h6 class="card-title">Total Payments</h6>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center p-3">
                    <h6 class="card-title">Paid</h6>
                    <h3 class="mb-0">{{ $stats['paid'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center p-3">
                    <h6 class="card-title">Unpaid</h6>
                    <h3 class="mb-0">{{ $stats['unpaid'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center p-3">
                    <h6 class="card-title">Partial</h6>
                    <h3 class="mb-0">{{ $stats['partial'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center p-3">
                    <h6 class="card-title">Overdue</h6>
                    <h3 class="mb-0">{{ $stats['overdue'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Search by invoice or student..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Student</th>
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
                            <td>
                                <strong>{{ $payment->invoice_number }}</strong>
                                <br>
                                <small class="text-muted">{{ $payment->payment_date->format('M d, Y') }}</small>
                            </td>
                            <td>
                                {{ $payment->student->user->name ?? 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $payment->student->student_code }}</small>
                            </td>
                            <td>
                                ${{ number_format($payment->amount, 2) }}
                                @if($payment->discount > 0)
                                <br>
                                <small class="text-success">- ${{ number_format($payment->discount, 2) }} discount</small>
                                @endif
                            </td>
                            <td>
                                <strong>${{ number_format($payment->paid_amount, 2) }}</strong>
                                @if($payment->remainingAmount > 0)
                                <br>
                                <small class="text-danger">${{ number_format($payment->remainingAmount, 2) }} remaining</small>
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
                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-sm" 
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm"
                                       data-bs-toggle="tooltip" title="Edit Payment">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this payment?')"
                                                data-bs-toggle="tooltip" title="Delete Payment">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-cash-coin display-4 d-block mb-2"></i>
                                    No payments found.
                                    @if(request()->anyFilled(['status', 'payment_method', 'search']))
                                        <p class="mt-2">Try adjusting your search filters.</p>
                                    @else
                                        <p class="mt-2">
                                            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                                                Record your first payment
                                            </a>
                                        </p>
                                    @endif
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

@push('scripts')
<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Auto-submit filters when changed
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit()
    })
    
    document.getElementById('payment_method').addEventListener('change', function() {
        this.form.submit()
    })
</script>
@endpush