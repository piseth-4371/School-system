<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\AttendanceDetail;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['teacher.user'])->latest();

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(20);
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        return view('attendances.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        Attendance::create($request->only(['date', 'status', 'teacher_id']));
        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['teacher.user', 'teacher.department', 'details.student.user']);
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $teachers = Teacher::with('user')->get();
        return view('attendances.edit', compact('attendance', 'teachers'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $attendance->update($request->only(['date', 'status', 'teacher_id']));
        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }

    public function showDetails(Attendance $attendance)
    {
        $attendance->load(['teacher.user', 'teacher.department', 'details.student.user']);
        return view('attendances.details', compact('attendance'));
    }

    public function createDetails(Attendance $attendance)
    {
        $attendance->load('teacher.department');
        $students = Student::where('department_id', $attendance->teacher->department_id)
            ->with('user')
            ->get();
            
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

        if (AttendanceDetail::where('attendance_id', $attendance->id)->where('student_id', $request->student_id)->exists()) {
            return back()->withInput()->with('error', 'This student already has attendance recorded.');
        }

        AttendanceDetail::create(array_merge($request->only(['student_id', 'session1', 'session2', 'session3', 'session4', 'remarks']), [
            'attendance_id' => $attendance->id
        ]));

        return redirect()->route('attendances.details.show', $attendance)->with('success', 'Student attendance added successfully.');
    }

    public function editDetail(AttendanceDetail $detail)
    {
        $detail->load(['attendance.teacher.department', 'student.user']);
        return view('attendances.details-edit', compact('detail'));
    }

    public function updateDetail(Request $request, AttendanceDetail $detail)
    {
        $request->validate([
            'session1' => 'required|in:present,absent,late,excused',
            'session2' => 'required|in:present,absent,late,excused',
            'session3' => 'required|in:present,absent,late,excused',
            'session4' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        $detail->update($request->only(['session1', 'session2', 'session3', 'session4', 'remarks']));
        return redirect()->route('attendances.details.show', $detail->attendance)->with('success', 'Attendance detail updated successfully.');
    }

    public function destroyDetail(AttendanceDetail $detail)
    {
        $attendanceId = $detail->attendance_id;
        $detail->delete();
        return redirect()->route('attendances.details.show', $attendanceId)->with('success', 'Attendance detail deleted successfully.');
    }
}