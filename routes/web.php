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
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\ClassYearController;
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
    
    // Resource Routes
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('exams', ExamController::class);
    Route::resource('timetables', TimetableController::class);
    Route::resource('class-years', ClassYearController::class);

    // Exam Results Routes
    Route::get('exams/{exam}/results', [ExamController::class, 'showResultsForm'])->name('exams.results.form');
    Route::post('exams/{exam}/results', [ExamController::class, 'storeResults'])->name('exams.results.store');

    // Custom Routes for Attendances and Payments
    Route::get('attendances/by-class/{classYear}', [AttendanceController::class, 'byClass'])->name('attendances.byClass');
    Route::get('payments/by-student/{student}', [PaymentController::class, 'byStudent'])->name('payments.byStudent');

    // Attendance Routes
    Route::resource('attendances', AttendanceController::class);

    // Attendance Details Routes
    Route::prefix('attendances')->group(function () {
        Route::get('{attendance}/details', [AttendanceController::class, 'showDetails'])->name('attendances.details.show');
        Route::get('{attendance}/details/create', [AttendanceController::class, 'createDetails'])->name('attendances.details.create');
        Route::post('{attendance}/details', [AttendanceController::class, 'storeDetails'])->name('attendances.details.store');
        Route::get('details/{detail}/edit', [AttendanceController::class, 'editDetail'])->name('attendances.details.edit');
        Route::put('details/{detail}', [AttendanceController::class, 'updateDetail'])->name('attendances.details.update');
        Route::delete('details/{detail}', [AttendanceController::class, 'destroyDetail'])->name('attendances.details.destroy');
    });
    // Reports route (should already exist based on your previous setup)
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/students', [ReportController::class, 'students'])->name('students');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/exams', [ReportController::class, 'exams'])->name('exams');
        Route::get('/financial-summary', [ReportController::class, 'financialSummary'])->name('financial-summary');
        Route::get('/export-students', [ReportController::class, 'exportStudents'])->name('export-students');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        
        // Academic Years
        Route::get('/academic-years', [SettingController::class, 'academicYears'])->name('academic-years');
        Route::post('/academic-years', [SettingController::class, 'storeAcademicYear'])->name('academic-years.store');
        Route::put('/academic-years/{academicYear}', [SettingController::class, 'updateAcademicYear'])->name('academic-years.update');
        Route::post('/academic-years/{academicYear}/set-current', [SettingController::class, 'setCurrentAcademicYear'])->name('set-current-academic-year');
        
        // System Configuration
        Route::get('/system-config', [SettingController::class, 'systemConfig'])->name('system-config');
        Route::post('/system-config', [SettingController::class, 'updateSystemConfig'])->name('system-config.update');
        
        // Class Management
        Route::get('/class-management', [SettingController::class, 'classManagement'])->name('class-management');
        Route::post('/classes', [SettingController::class, 'storeClass'])->name('classes.store');
        Route::put('/classes/{class}', [SettingController::class, 'updateClass'])->name('classes.update');
        
        // Class Year Management
        Route::get('/class-year-management', [SettingController::class, 'classYearManagement'])->name('class-year-management');
        Route::post('/class-years', [SettingController::class, 'storeClassYear'])->name('class-years.store');
        Route::put('/class-years/{classYear}', [SettingController::class, 'updateClassYear'])->name('class-years.update');
        
        // User Management - FIXED ROUTE NAMES
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
        
        // Backup Routes - KEEP THESE
        Route::prefix('backup')->name('backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::post('/create', [BackupController::class, 'create'])->name('create');
            Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
            Route::post('/restore', [BackupController::class, 'restore'])->name('restore');
        });
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete-photo');
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    });

    // REMOVE THESE DUPLICATE BACKUP ROUTES (they conflict with the ones in settings)
    // Route::prefix('backup')->name('backup.')->group(function () {
    //     Route::get('/', [BackupController::class, 'index'])->name('index');
    //     Route::post('/create', [BackupController::class, 'create'])->name('create');
    //     Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
    //     Route::post('/restore', [BackupController::class, 'restore'])->name('restore');
    // });
});