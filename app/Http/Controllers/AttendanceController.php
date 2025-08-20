<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassYear;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Display attendance records
    public function index()
    {
        $attendances = Attendance::with('teacher')->paginate(10);
        return view('attendances.index', compact('attendances'));
    }

    // Show form to create a new attendance record
    public function create()
    {
        $teachers = \App\Models\Teacher::all();
        $classYears = ClassYear::where('is_active', true)->get();
        
        return view('attendances.create', compact('teachers', 'classYears'));
    }

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

    // Show form to edit an existing attendance record
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $teachers = \App\Models\Teacher::all();
        return view('attendances.edit', compact('attendance', 'teachers'));
    }

    // Update an existing attendance record
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'date' => $request->date,
            'status' => $request->status,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully.');
    }

    // Delete an attendance record
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully.');
    }
}