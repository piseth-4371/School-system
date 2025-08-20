@extends('layouts.app')

@section('title', 'Record New Payment')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Record New Payment</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('payments.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student *</label>
                            <select class="form-select" id="student_id" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach ($students as $stu)
                                    <option value="{{ $stu->id }}" @selected(old('student_id')==$stu->id)>
                                        {{ $stu->student_code }} — {{ $stu->user->name ?? 'N/A' }} - {{ $stu->department->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">Invoice Number *</label>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                   value="{{ old('invoice_number') }}" required>
                            @error('invoice_number') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount ($) *</label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" min="0" value="{{ old('amount') }}" required>
                                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount ($)</label>
                                    <input type="number" class="form-control" id="discount" name="discount" 
                                           step="0.01" min="0" value="{{ old('discount', 0) }}">
                                    @error('discount') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">Paid Amount ($) *</label>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" 
                                   step="0.01" min="0" value="{{ old('paid_amount') }}" required>
                            @error('paid_amount') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date *</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date *</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" 
                                           value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                    @error('due_date') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method *</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                            @error('payment_method') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Calculate remaining amount and update status message
    function updatePaymentInfo() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        
        const totalAmount = amount - discount;
        const remaining = totalAmount - paid;
        
        let status = '';
        if (paid >= totalAmount) {
            status = '✅ Payment will be marked as PAID';
        } else if (paid > 0) {
            status = `⚠️ Payment will be marked as PARTIAL ($${remaining.toFixed(2)} remaining)`;
        } else {
            status = '❌ Payment will be marked as UNPAID';
        }
        
        // Show status message
        let statusElement = document.getElementById('payment-status');
        if (!statusElement) {
            statusElement = document.createElement('div');
            statusElement.id = 'payment-status';
            statusElement.className = 'alert alert-info mt-3';
            document.querySelector('form').appendChild(statusElement);
        }
        statusElement.innerHTML = status;
    }

    // Add event listeners
    document.getElementById('amount').addEventListener('input', updatePaymentInfo);
    document.getElementById('discount').addEventListener('input', updatePaymentInfo);
    document.getElementById('paid_amount').addEventListener('input', updatePaymentInfo);

    // Initial calculation
    updatePaymentInfo();
</script>
@endpush