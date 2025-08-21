@extends('layouts.app')

@section('title', 'Financial Summary Report')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Financial Summary Report</h1>
                <div class="btn-group">
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Reports
                    </a>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', $startDate ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', $endDate ?? '') }}">
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
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-currency-dollar display-4"></i>
                    </div>
                    <h5 class="card-title">Total Revenue</h5>
                    <h3 class="card-text text-primary">${{ number_format($summary['total_revenue'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle display-4"></i>
                    </div>
                    <h5 class="card-title">Paid Payments</h5>
                    <h3 class="card-text text-success">{{ $summary['paid_count'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-clock-history display-4"></i>
                    </div>
                    <h5 class="card-title">Pending Payments</h5>
                    <h3 class="card-text text-warning">{{ $summary['unpaid_count'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger mb-2">
                        <i class="bi bi-exclamation-triangle display-4"></i>
                    </div>
                    <h5 class="card-title">Overdue Payments</h5>
                    <h3 class="card-text text-danger">{{ $summary['overdue_count'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Detailed Reports -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Revenue</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Payment Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice #</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Create a safe fallback for payments data
                            $paymentsData = $payments ?? [];
                        @endphp
                        
                        @forelse($paymentsData as $payment)
                        <tr>
                            <td>{{ isset($payment->payment_date) ? $payment->payment_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $payment->invoice_number ?? 'N/A' }}</td>
                            <td>{{ $payment->student->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($payment->paid_amount ?? 0, 2) }}</td>
                            <td>
                                @if(isset($payment->payment_method))
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if(isset($payment->status))
                                    <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'overdue' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No payment records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyData = {!! json_encode($monthlyData ?? []) !!};
    
    if (Object.keys(monthlyData).length > 0) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: Object.values(monthlyData),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('monthlyRevenueChart').innerHTML = 
            '<div class="text-center text-muted p-4">No revenue data available</div>';
    }

    // Payment Methods Chart
    const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const paymentMethods = {!! json_encode($paymentMethods ?? []) !!};
    
    if (Object.keys(paymentMethods).length > 0) {
        new Chart(methodsCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(paymentMethods),
                datasets: [{
                    data: Object.values(paymentMethods).map(method => method.amount),
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0']
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('paymentMethodsChart').innerHTML = 
            '<div class="text-center text-muted p-4">No payment method data available</div>';
    }
</script>
@endpush