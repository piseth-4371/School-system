<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
use App\Models\ClassYear;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'recorder'])->latest();

        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('student.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(20);

        $stats = [
            'total' => Payment::count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'unpaid' => Payment::where('status', 'unpaid')->count(),
            'partial' => Payment::where('status', 'partial')->count(),
            'overdue' => Payment::where('due_date', '<', now())->where('status', '!=', 'paid')->count(),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->whereHas('user', function($query) {
            $query->where('role', 'student');
        })->get();

        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_number' => 'required|unique:payments,invoice_number',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:payment_date',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer,check',
            'description' => 'nullable|string|max:500',
        ]);

        // Calculate status based on amounts
        $totalAmount = $validated['amount'] - ($validated['discount'] ?? 0);
        if ($validated['paid_amount'] >= $totalAmount) {
            $status = 'paid';
        } elseif ($validated['paid_amount'] > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        // Check if payment is overdue
        if ($status != 'paid' && $validated['due_date'] < now()) {
            $status = 'overdue';
        }

        $payment = Payment::create([
            'student_id' => $validated['student_id'],
            'invoice_number' => $validated['invoice_number'],
            'amount' => $validated['amount'],
            'discount' => $validated['discount'] ?? 0,
            'paid_amount' => $validated['paid_amount'],
            'payment_date' => $validated['payment_date'],
            'due_date' => $validated['due_date'],
            'status' => $status,
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'],
            'recorded_by' => Auth::id(),
        ]);

        return redirect()->route('payments.index')
                        ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student.user', 'student.department', 'student.classYear.class', 'recorder']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $students = Student::with('user')->whereHas('user', function($query) {
            $query->where('role', 'student');
        })->get();

        $payment->load('student.user');
        return view('payments.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_number' => 'required|unique:payments,invoice_number,' . $payment->id,
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:payment_date',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer,check',
            'description' => 'nullable|string|max:500',
        ]);

        // Calculate status based on amounts
        $totalAmount = $validated['amount'] - ($validated['discount'] ?? 0);
        if ($validated['paid_amount'] >= $totalAmount) {
            $status = 'paid';
        } elseif ($validated['paid_amount'] > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        // Check if payment is overdue
        if ($status != 'paid' && $validated['due_date'] < now()) {
            $status = 'overdue';
        }

        $payment->update([
            'student_id' => $validated['student_id'],
            'invoice_number' => $validated['invoice_number'],
            'amount' => $validated['amount'],
            'discount' => $validated['discount'] ?? 0,
            'paid_amount' => $validated['paid_amount'],
            'payment_date' => $validated['payment_date'],
            'due_date' => $validated['due_date'],
            'status' => $status,
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('payments.index')
                        ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
                        ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Show payments for a specific student
     */
    public function byStudent($studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);
        $payments = Payment::where('student_id', $studentId)
                         ->with('recorder')
                         ->latest()
                         ->paginate(15);

        return view('payments.by-student', compact('payments', 'student'));
    }
}