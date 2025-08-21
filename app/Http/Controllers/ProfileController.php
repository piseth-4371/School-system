<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ClassYear;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profileData = null;

        // Load additional profile data based on role
        if ($user->role === 'student') {
            $profileData = Student::with(['department', 'classYear.class', 'classYear.academicYear'])
                                ->where('user_id', $user->id)
                                ->first();
        } elseif ($user->role === 'teacher') {
            $profileData = Teacher::with(['department'])
                                ->where('user_id', $user->id)
                                ->first();
        }

        return view('profile.show', compact('user', 'profileData'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profileData = null;

        if ($user->role === 'student') {
            $profileData = Student::where('user_id', $user->id)->first();
        } elseif ($user->role === 'teacher') {
            $profileData = Teacher::where('user_id', $user->id)->first();
        }

        return view('profile.edit', compact('user', 'profileData'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update basic user info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update profile data based on role
        if ($user->role === 'student') {
            $this->updateStudentProfile($user, $validated);
        } elseif ($user->role === 'teacher') {
            $this->updateTeacherProfile($user, $validated);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $this->updateProfilePhoto($user, $request->file('profile_photo'));
        }

        // Handle password change
        if ($request->filled('current_password')) {
            $this->updatePassword($user, $validated);
        }

        return redirect()->route('profile.show')
                        ->with('success', 'Profile updated successfully.');
    }

    private function updateStudentProfile($user, $data)
    {
        $student = Student::where('user_id', $user->id)->first();
        if ($student) {
            $student->update([
                'phone' => $data['phone'] ?? $student->phone,
                'address' => $data['address'] ?? $student->address,
            ]);
        }
    }

    private function updateTeacherProfile($user, $data)
    {
        $teacher = Teacher::where('user_id', $user->id)->first();
        if ($teacher) {
            $teacher->update([
                'phone' => $data['phone'] ?? $teacher->phone,
            ]);
        }
    }

   private function updateProfilePhoto($user, $photo)
    {
        // Delete old profile photo if exists
        if ($user->profile_photo) {
            Storage::delete('public/profile-photos/' . $user->profile_photo);
        }

        // Generate unique filename
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
        
        // Store the file
        $photo->storeAs('public/profile-photos', $filename);

        // Update user record
        $user->update(['profile_photo' => $filename]);
    }
    
    private function updatePassword($user, $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return redirect()->back()
                            ->withErrors(['current_password' => 'Current password is incorrect.'])
                            ->withInput();
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::delete('public/profile-photos/' . $user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return redirect()->route('profile.edit')
                        ->with('success', 'Profile photo removed successfully.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $stats = [];

        switch ($user->role) {
            case 'student':
                $stats = $this->getStudentDashboardStats($user);
                break;
            case 'teacher':
                $stats = $this->getTeacherDashboardStats($user);
                break;
            case 'admin':
                $stats = $this->getAdminDashboardStats();
                break;
            case 'accountant':
                $stats = $this->getAccountantDashboardStats();
                break;
        }

        return view('profile.dashboard', compact('user', 'stats'));
    }

    private function getStudentDashboardStats($user)
    {
        $student = Student::with(['classYear', 'payments', 'attendances'])->where('user_id', $user->id)->first();

        return [
            'total_payments' => $student->payments->sum('paid_amount'),
            'attendance_rate' => $this->calculateAttendanceRate($student),
            'upcoming_exams' => \App\Models\Exam::where('class_year_id', $student->class_year_id)
                                        ->where('exam_date', '>=', now())
                                        ->count(),
            'pending_payments' => $student->payments->where('status', 'unpaid')->count(),
        ];
    }

    private function getTeacherDashboardStats($user)
    {
        $teacher = Teacher::where('user_id', $user->id)->first();

        return [
            'total_classes' => \App\Models\Timetable::where('teacher_id', $teacher->id)->count(),
            'upcoming_classes' => \App\Models\Timetable::where('teacher_id', $teacher->id)
                                        ->where('day_id', now()->dayOfWeek)
                                        ->count(),
            'students_count' => \App\Models\Student::where('department_id', $teacher->department_id)->count(),
            'exams_scheduled' => \App\Models\Exam::where('class_year_id', 
                \App\Models\ClassYear::where('class_id', 
                    \App\Models\SchoolClass::where('department_id', $teacher->department_id)->pluck('id')
                )->pluck('id')
            )->count(),
        ];
    }

    private function getAdminDashboardStats()
    {
        return [
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'total_payments' => \App\Models\Payment::sum('paid_amount'),
            'active_classes' => \App\Models\SchoolClass::where('is_active', true)->count(),
        ];
    }

    private function getAccountantDashboardStats()
    {
        return [
            'total_revenue' => \App\Models\Payment::sum('paid_amount'),
            'pending_payments' => \App\Models\Payment::where('status', 'unpaid')->count(),
            'today_payments' => \App\Models\Payment::whereDate('payment_date', today())->sum('paid_amount'),
            'overdue_payments' => \App\Models\Payment::where('status', 'overdue')->count(),
        ];
    }

    private function calculateAttendanceRate($student)
    {
        $totalSessions = $student->attendances->count() * 4; // 4 sessions per day
        if ($totalSessions === 0) return 0;

        $presentSessions = 0;
        foreach ($student->attendances as $attendance) {
            $presentSessions += ($attendance->session1 === 'present' ? 1 : 0);
            $presentSessions += ($attendance->session2 === 'present' ? 1 : 0);
            $presentSessions += ($attendance->session3 === 'present' ? 1 : 0);
            $presentSessions += ($attendance->session4 === 'present' ? 1 : 0);
        }

        return round(($presentSessions / $totalSessions) * 100, 2);
    }
}