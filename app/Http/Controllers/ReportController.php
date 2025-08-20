<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Department;
use App\Models\ClassYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function students(Request $request)
    {
        $query = Student::with(['user', 'department', 'classYear.class', 'classYear.academicYear']);

        // Apply filters
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('class_year_id') && $request->class_year_id) {
            $query->where('class_year_id', $request->class_year_id);
        }

        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        $students = $query->get();
        $departments = Department::where('is_active', true)->get();
        $classYears = ClassYear::with(['class', 'academicYear'])->where('is_active', true)->get();

        return view('reports.students', compact('students', 'departments', 'classYears'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['student.user', 'recorder']);

        // Date filters
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->get();
        $totalAmount = $payments->sum('paid_amount');
        $totalRecords = $payments->count();

        return view('reports.payments', compact('payments', 'totalAmount', 'totalRecords'));
    }

    public function attendance(Request $request)
    {
        $query = Attendance::with(['classYear.class', 'classYear.academicYear', 'teacher.user']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->has('class_year_id') && $request->class_year_id) {
            $query->where('class_year_id', $request->class_year_id);
        }

        $attendance = $query->get();
        $classYears = ClassYear::with(['class', 'academicYear'])->where('is_active', true)->get();

        return view('reports.attendance', compact('attendance', 'classYears'));
    }

    public function exams(Request $request)
    {
        $query = Exam::with(['classYear.class', 'classYear.academicYear', 'subject', 'classroom']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('exam_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('exam_date', '<=', $request->end_date);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('class_year_id') && $request->class_year_id) {
            $query->where('class_year_id', $request->class_year_id);
        }

        $exams = $query->get();
        $classYears = ClassYear::with(['class', 'academicYear'])->where('is_active', true)->get();

        return view('reports.exams', compact('exams', 'classYears'));
    }

    public function financialSummary(Request $request)
    {
        $query = Payment::query();

        // Date range
        $startDate = $request->start_date ?: now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?: now()->format('Y-m-d');

        $query->whereBetween('payment_date', [$startDate, $endDate]);

        $payments = $query->get();

        // Summary data
        $summary = [
            'total_revenue' => $payments->sum('paid_amount'),
            'total_payments' => $payments->count(),
            'paid_count' => $payments->where('status', 'paid')->count(),
            'partial_count' => $payments->where('status', 'partial')->count(),
            'unpaid_count' => $payments->where('status', 'unpaid')->count(),
            'overdue_count' => $payments->where('status', 'overdue')->count(),
        ];

        // Monthly breakdown
        $monthlyData = $payments->groupBy(function($payment) {
            return Carbon::parse($payment->payment_date)->format('Y-m');
        })->map(function($monthPayments) {
            return $monthPayments->sum('paid_amount');
        });

        // Payment method breakdown
        $paymentMethods = $payments->groupBy('payment_method')->map(function($methodPayments) {
            return [
                'count' => $methodPayments->count(),
                'amount' => $methodPayments->sum('paid_amount')
            ];
        });

        return view('reports.financial-summary', compact('summary', 'monthlyData', 'paymentMethods', 'startDate', 'endDate'));
    }

    public function exportStudents(Request $request)
    {
        $query = Student::with(['user', 'department', 'classYear.class', 'classYear.academicYear']);

        // Apply filters same as students report
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $students = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Student Code', 'Name', 'Email', 'Gender', 'Department', 
                'Class', 'Academic Year', 'Phone', 'Enrollment Date', 'Status'
            ]);

            // Data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->student_code,
                    $student->user->name ?? 'N/A',
                    $student->user->email ?? 'N/A',
                    ucfirst($student->gender),
                    $student->department->name ?? 'N/A',
                    $student->classYear->class->name ?? 'N/A',
                    $student->classYear->academicYear->name ?? 'N/A',
                    $student->phone,
                    $student->enrolled_date->format('Y-m-d'),
                    $student->is_active ? 'Active' : 'Inactive'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}