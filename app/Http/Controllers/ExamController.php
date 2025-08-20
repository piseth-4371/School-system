<?php

namespace App\Http\Controllers;
use App\Models\Exam;
use App\Models\ExamDetail;
use App\Models\ClassYear;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Exam::with(['classYear.class', 'classYear.academicYear', 'subject', 'classroom', 'teacher'])
                    ->latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('class_year') && $request->class_year != '') {
            $query->where('class_year_id', $request->class_year);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'upcoming') {
                $query->where('exam_date', '>=', now());
            } elseif ($request->status == 'completed') {
                $query->where('exam_date', '<', now());
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subject', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $exams = $query->paginate(20);

        $classYears = ClassYear::with(['class', 'academicYear'])->get();
        $examTypes = ['quiz', 'midterm', 'final', 'assignment'];

        return view('exams.index', compact('exams', 'classYears', 'examTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classYears = ClassYear::with(['class', 'academicYear'])->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();
        $teachers = \App\Models\Teacher::where('is_active', true)->get(); // Add this line
        $examTypes = ['quiz', 'midterm', 'final', 'assignment'];

        return view('exams.create', compact('classYears', 'subjects', 'classrooms', 'teachers', 'examTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_year_id' => 'required|exists:class_years,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'classroom_id' => 'required|exists:classrooms,id',
            'type' => 'required|in:quiz,midterm,final,assignment',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:total_marks',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam)
                        ->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $exam->load([
            'classYear.class', 
            'classYear.academicYear', 
            'subject', 
            'classroom',
            'teacher',
            'examDetails.student.user'
        ]);

        // Get students from the class year
        $classYear = $exam->classYear;
        $students = Student::where('department_id', $classYear->department_id)
                         ->with('user')
                         ->get();

        return view('exams.show', compact('exam', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        $classYears = ClassYear::with(['class', 'academicYear'])->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->get();
        $teachers = \App\Models\Teacher::where('is_active', true)->get();
        $examTypes = ['quiz', 'midterm', 'final', 'assignment'];

        return view('exams.edit', compact('exam', 'classYears', 'subjects', 'classrooms', 'teachers', 'examTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_year_id' => 'required|exists:class_years,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'classroom_id' => 'required|exists:classrooms,id',
            'type' => 'required|in:quiz,midterm,final,assignment',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:total_marks',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.show', $exam)
                        ->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index')
                        ->with('success', 'Exam deleted successfully.');
    }

    /**
     * Store exam results for students
     */
    public function storeResults(Request $request, Exam $exam)
    {
        $request->validate([
            'results' => 'required|array',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.score' => 'nullable|numeric|min:0|max:' . $exam->total_marks,
            'results.*.remarks' => 'nullable|string|max:255',
        ]);

        foreach ($request->results as $result) {
            if (isset($result['score'])) {
                $examDetail = ExamDetail::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $result['student_id']
                    ],
                    [
                        'score' => $result['score'],
                        'grade' => $this->calculateGrade($result['score'], $exam->total_marks),
                        'remarks' => $result['remarks'] ?? null
                    ]
                );
            }
        }

        return redirect()->route('exams.show', $exam)
                        ->with('success', 'Exam results saved successfully.');
    }

    /**
     * Calculate grade based on score
     */
    private function calculateGrade($score, $totalMarks)
    {
        if ($totalMarks == 0) return 'N/A';

        $percentage = ($score / $totalMarks) * 100;

        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'A-';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 65) return 'B-';
        if ($percentage >= 60) return 'C+';
        if ($percentage >= 55) return 'C';
        if ($percentage >= 50) return 'C-';
        if ($percentage >= 45) return 'D+';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    /**
     * Show exam results entry form
     */
    public function showResultsForm(Exam $exam)
    {
        $exam->load(['classYear.class', 'classYear.department', 'subject']);
        
        // Debug: Check if classYear and department exist
        if (!$exam->classYear) {
            return back()->with('error', 'Class year not found for this exam.');
        }
        
        if (!$exam->classYear->department) {
            return back()->with('error', 'Department not found for this class year.');
        }
        
        // Get students from the same department as the exam's class year
        $students = Student::with(['user', 'examDetails' => function($query) use ($exam) {
                            $query->where('exam_id', $exam->id);
                        }])
                        ->where('department_id', $exam->classYear->department_id)
                        ->get();

        // If no students found, try to get all students (for debugging)
        if ($students->isEmpty()) {
            // For debugging, let's see if there are any students at all
            $allStudents = Student::count();
            $departmentStudents = Student::where('department_id', $exam->classYear->department_id)->count();
            
            \Log::info("Exam Debug - All students: {$allStudents}, Department students: {$departmentStudents}, Department ID: {$exam->classYear->department_id}");
        }

        return view('exams.results', compact('exam', 'students'));
    }

}