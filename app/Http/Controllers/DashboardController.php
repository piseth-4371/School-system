<?php

namespace App\Http\Controllers;
use App\Models\ClassYear;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Exam;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_departments' => Department::count(),
            'active_payments' => Payment::where('status', 'paid')->sum('paid_amount'),
        ];

        $recentStudents = Student::with(['user', 'department'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['student.user'])
            ->latest()
            ->take(5)
            ->get();

        $todayAttendance = Attendance::whereDate('date', today())->count();

        $upcomingExams = Exam::with(['subject', 'classYear.class'])
            ->where('exam_date', '>=', today())
            ->orderBy('exam_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats', 
            'recentStudents', 
            'recentPayments', 
            'todayAttendance',
            'upcomingExams'
        ));
    }

    public function getChartData(Request $request)
    {
        $range = $request->get('range', 'monthly');
        
        if ($range === 'monthly') {
            $data = Payment::whereYear('payment_date', now()->year)
                ->selectRaw('MONTH(payment_date) as month, SUM(paid_amount) as total')
                ->groupBy('month')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [Carbon::create()->month($item->month)->format('F') => $item->total];
                });
        } else {
            $data = Payment::whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month)
                ->selectRaw('DATE(payment_date) as date, SUM(paid_amount) as total')
                ->groupBy('date')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->total];
                });
        }

        return response()->json($data);
    }
}