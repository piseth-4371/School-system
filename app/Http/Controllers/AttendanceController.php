<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\ClassYear;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Display attendance records
    public function index(Request $request)
    {
        $query = Attendance::with(['teacher.user'])->latest();

        // Apply filters
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(20);

        return view('attendances.index', compact('attendances'));
    }

    // Show form to create a new attendance record
    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $classYears = ClassYear::where('is_active', true)->get();
        
        return view('attendances.create', compact('teachers', 'classYears'));
    }

    // Store a new attendance record
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        Attendance::create([
            'date' => $request->date,
            'status' => $request->status,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    // Show a single attendance record
    public function show(Attendance $attendance)
    {
        $attendance->load(['teacher.user', 'details.student.user']);
        return view('attendances.show', compact('attendance'));
    }

    // Show form to edit an existing attendance record
    public function edit(Attendance $attendance)
    {
        $teachers = Teacher::with('user')->get();
        return view('attendances.edit', compact('attendance', 'teachers'));
    }

    // Update an existing attendance record
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $attendance->update([
            'date' => $request->date,
            'status' => $request->status,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    // Delete an attendance record
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function showDetails(Attendance $attendance)
    {
        $attendance->load(['teacher.user', 'details.student.user']);
        return view('attendances.details', compact('attendance'));
    }

    public function createDetails(Attendance $attendance)
    {
        // Get students for this attendance (based on department, class year, etc.)
        $students = Student::where('department_id', $attendance->teacher->department_id)->get();
        return view('attendances.details-create', compact('attendance', 'students'));
    }

    public function storeDetails(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'session1' => 'required|in:present,absent,late,excused',
            'session2' => 'required|in:present,absent,late,excused',
            'session3' => 'required|in:present,absent,late,excused',
            'session4' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        \App\Models\AttendanceDetail::create([
            'attendance_id' => $attendance->id,
            'student_id' => $request->student_id,
            'session1' => $request->session1,
            'session2' => $request->session2,
            'session3' => $request->session3,
            'session4' => $request->session4,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendances.details.show', $attendance)
                        ->with('success', 'Attendance details added successfully.');
    }
}