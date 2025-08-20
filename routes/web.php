<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\ProfileController; 

use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes (Requires authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes for Students, Teachers, Departments, Attendances, Payments
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payments', PaymentController::class);

    Route::resource('exams', ExamController::class);
    Route::get('exams/{exam}/results', [ExamController::class, 'showResultsForm'])->name('exams.results.form');
    Route::post('exams/{exam}/results', [ExamController::class, 'storeResults'])->name('exams.results.store');

    // Custom Routes for Attendances and Payments
    Route::get('attendances/class/{classYear}', [AttendanceController::class, 'byClass'])->name('attendances.byClass');
    Route::get('payments/student/{student}', [PaymentController::class, 'byStudent'])->name('payments.byStudent');

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/students', [ReportController::class, 'students'])->name('reports.students');
        Route::get('/payments', [ReportController::class, 'payments'])->name('reports.payments');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('/exams', [ReportController::class, 'exams'])->name('reports.exams');
        Route::get('/financial-summary', [ReportController::class, 'financialSummary'])->name('reports.financial-summary');
        Route::get('/export-students', [ReportController::class, 'exportStudents'])->name('reports.export-students');
    });

    // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        
        // Academic Years
        Route::get('/academic-years', [SettingController::class, 'academicYears'])->name('settings.academic-years');
        Route::post('/academic-years', [SettingController::class, 'storeAcademicYear'])->name('settings.academic-years.store');
        Route::put('/academic-years/{academicYear}', [SettingController::class, 'updateAcademicYear'])->name('settings.academic-years.update');
        Route::post('/academic-years/{academicYear}/set-current', [SettingController::class, 'setCurrentAcademicYear'])->name('settings.set-current-academic-year');
        
        // System Configuration
        Route::get('/system-config', [SettingController::class, 'systemConfig'])->name('settings.system-config');
        Route::post('/system-config', [SettingController::class, 'updateSystemConfig'])->name('settings.system-config.update');
        
        // Class Management
        Route::get('/class-management', [SettingController::class, 'classManagement'])->name('settings.class-management');
        Route::post('/classes', [SettingController::class, 'storeClass'])->name('settings.classes.store');
        Route::put('/classes/{class}', [SettingController::class, 'updateClass'])->name('settings.classes.update');
        
        // Class Year Management
        Route::get('/class-year-management', [SettingController::class, 'classYearManagement'])->name('settings.class-year-management');
        Route::post('/class-years', [SettingController::class, 'storeClassYear'])->name('settings.class-years.store');
        Route::put('/class-years/{classYear}', [SettingController::class, 'updateClassYear'])->name('settings.class-years.update');
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('profile.dashboard');
    });

    // User Management
    Route::resource('user-management', UserManagementController::class)->names('user-management');

    // Backup Routes
    Route::prefix('backup')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/create', [BackupController::class, 'create'])->name('backup.create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
        Route::post('/restore', [BackupController::class, 'restore'])->name('backup.restore');
    });
});
