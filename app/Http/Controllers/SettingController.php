<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\ClassYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $departments = Department::where('is_active', true)->get();
        $classes = SchoolClass::with('department')->where('is_active', true)->get();
        
        return view('settings.index', compact('academicYears', 'departments', 'classes'));
    }

    public function academicYears()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('settings.academic-years', compact('academicYears'));
    }

    public function storeAcademicYear(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:academic_years,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean'
        ]);

        // If this is set as current, remove current flag from others
        if ($request->is_current) {
            AcademicYear::where('is_current', true)->update(['is_current' => false]);
        }

        AcademicYear::create($validated);

        return redirect()->route('settings.academic-years')
                        ->with('success', 'Academic year created successfully.');
    }

    public function updateAcademicYear(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:academic_years,code,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean'
        ]);

        // If this is set as current, remove current flag from others
        if ($request->is_current) {
            AcademicYear::where('is_current', true)->where('id', '!=', $academicYear->id)->update(['is_current' => false]);
        }

        $academicYear->update($validated);

        return redirect()->route('settings.academic-years')
                        ->with('success', 'Academic year updated successfully.');
    }

    public function setCurrentAcademicYear(AcademicYear $academicYear)
    {
        AcademicYear::where('is_current', true)->update(['is_current' => false]);
        $academicYear->update(['is_current' => true]);

        return redirect()->route('settings.academic-years')
                        ->with('success', 'Current academic year set successfully.');
    }

    public function systemConfig()
    {
        $config = [
            'school_name' => config('app.name', 'University School Management System'),
            'school_address' => config('school.address', ''),
            'school_phone' => config('school.phone', ''),
            'school_email' => config('school.email', ''),
            'currency' => config('school.currency', 'USD'),
            'date_format' => config('school.date_format', 'Y-m-d'),
        ];

        return view('settings.system-config', compact('config'));
    }

    public function updateSystemConfig(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:255',
            'currency' => 'required|string|max:3',
            'date_format' => 'required|string|in:Y-m-d,d-m-Y,m-d-Y,Y/m/d,d/m/Y,m/d/Y',
        ]);

        // Update config values (you might want to store these in database or config file)
        foreach ($validated as $key => $value) {
            config(["school.{$key}" => $value]);
        }

        // Clear config cache
        Cache::forget('school_settings');

        return redirect()->route('settings.system-config')
                        ->with('success', 'System configuration updated successfully.');
    }

    public function classManagement()
    {
        $classes = SchoolClass::with('department')->get();
        $departments = Department::where('is_active', true)->get();
        $academicYears = AcademicYear::where('is_active', true)->get();

        return view('settings.class-management', compact('classes', 'departments', 'academicYears'));
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:classes,code',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        SchoolClass::create($validated);

        return redirect()->route('settings.class-management')
                        ->with('success', 'Class created successfully.');
    }

    public function updateClass(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:classes,code,' . $class->id,
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $class->update($validated);

        return redirect()->route('settings.class-management')
                        ->with('success', 'Class updated successfully.');
    }

     public function classYearManagement()
    {
        $classYears = ClassYear::with(['class', 'academicYear'])->get();
        $classes = SchoolClass::where('is_active', true)->get();
        $academicYears = AcademicYear::where('is_active', true)->get();

        return view('settings.class-year-management', compact('classYears', 'classes', 'academicYears'));
    }

   public function storeClassYear(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:first,second,summer',
            'is_active' => 'boolean'
        ]);

        // Get the department_id from the selected class
        $class = SchoolClass::findOrFail($validated['class_id']);
        $validated['department_id'] = $class->department_id;

        // Check if combination already exists
        $exists = ClassYear::where('class_id', $request->class_id)
                        ->where('year_id', $request->year_id)
                        ->where('semester', $request->semester)
                        ->exists();

        if ($exists) {
            return redirect()->back()
                            ->with('error', 'This class year combination already exists.');
        }

        ClassYear::create($validated);

        return redirect()->route('settings.class-year-management')
                        ->with('success', 'Class year created successfully.');
    }

    public function updateClassYear(Request $request, ClassYear $classYear)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:first,second,summer',
            'is_active' => 'boolean'
        ]);

        // Get the department_id from the selected class
        $class = SchoolClass::findOrFail($validated['class_id']);
        $validated['department_id'] = $class->department_id;

        // Check if combination already exists (excluding current)
        $exists = ClassYear::where('class_id', $request->class_id)
                        ->where('year_id', $request->year_id)
                        ->where('semester', $request->semester)
                        ->where('id', '!=', $classYear->id)
                        ->exists();

        if ($exists) {
            return redirect()->back()
                            ->with('error', 'This class year combination already exists.');
        }

        $classYear->update($validated);

        return redirect()->route('settings.class-year-management')
                        ->with('success', 'Class year updated successfully.');
    }
}