<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Department;
use App\Models\ClassYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['user', 'department'])
            ->latest()
            ->get();
            
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $classYears = ClassYear::with(['class', 'academicYear'])
            ->where('is_active', true)
            ->get();
            
        return view('students.create', compact('departments', 'classYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'student_code' => 'required|unique:students,student_code',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'enrolled_date' => 'required|date',
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where('is_active', true)
            ]
        ]);

        DB::beginTransaction();

        try {
            // Create User first
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student'
            ]);

            // Create Student profile
            Student::create([
                'user_id' => $user->id,
                'student_code' => $validated['student_code'],
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'enrolled_date' => $validated['enrolled_date'],
                'department_id' => $validated['department_id']
            ]);

            DB::commit();

            return redirect()->route('students.index')
                            ->with('success', 'Student created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                        ->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'department', 'payments', 'attendances']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load(['user']);
        $departments = Department::where('is_active', true)->get();
            
        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id)
            ],
            'student_code' => [
                'required',
                Rule::unique('students', 'student_code')->ignore($student->id)
            ],
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'enrolled_date' => 'required|date',
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where('is_active', true)
            ]
        ]);

        DB::beginTransaction();

        try {
            // Update User
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update Student
            $student->update([
                'student_code' => $validated['student_code'],
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'enrolled_date' => $validated['enrolled_date'],
                'department_id' => $validated['department_id']
            ]);

            DB::commit();

            return redirect()->route('students.index')
                            ->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                        ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            $student->delete();
            if ($student->user) {
                $student->user->delete();
            }

            DB::commit();

            return redirect()->route('students.index')
                            ->with('success', 'Student deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
}