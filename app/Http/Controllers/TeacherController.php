<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\SchoolClass;
use App\Models\ClassYear;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'department'])->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('teachers.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'teacher_code' => 'required|unique:teachers,teacher_code',
            'gender' => 'required|in:male,female,other',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'joined_date' => 'required|date',
            'department_id' => 'required|exists:departments,id'
        ]);

        DB::beginTransaction();

        try {
            // Create User first
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'teacher'
            ]);

            // Create Teacher profile
            Teacher::create([
                'user_id' => $user->id,
                'teacher_code' => $validated['teacher_code'],
                'gender' => $validated['gender'],
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'],
                'joined_date' => $validated['joined_date'],
                'department_id' => $validated['department_id']
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                            ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                        ->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    public function show(Teacher $teacher)
    {
        $teacher->load([
            'user', 
            'department', 
            'classes.department',
            'exams.subject',
            'timetables.day',
            'timetables.subject',
            'timetables.classYear.class'
        ]);

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $departments = Department::where('is_active', true)->get();
        return view('teachers.edit', compact('teacher', 'departments'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'teacher_code' => 'required|unique:teachers,teacher_code,' . $teacher->id,
            'gender' => 'required|in:male,female,other',
            'qualification' => 'required|string',
            'specialization' => 'required|string',
            'joined_date' => 'required|date',
            'department_id' => 'required|exists:departments,id'
        ]);

        // Update the user's information
        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update the teacher's information
        $teacher->update([
            'teacher_code' => $validated['teacher_code'],
            'gender' => $validated['gender'],
            'qualification' => $validated['qualification'],
            'specialization' => $validated['specialization'],
            'joined_date' => $validated['joined_date'],
            'department_id' => $validated['department_id']
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        // Soft delete the teacher and user
        $teacher->delete();
        $teacher->user->delete();

        return redirect()->route('teachers.index')
                        ->with('success', 'Teacher deleted successfully.');
    }
}
