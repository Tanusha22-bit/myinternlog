<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\UniversitySupervisorStudentController;
use App\Http\Controllers\UniversitySupervisorProfileController;
use App\Http\Controllers\UniversitySupervisorDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
//Route::get('/dashboard/student', fn() => view('dashboards.student'))->middleware('auth')->name('student.dashboard');
Route::get('/dashboard/industry', fn() => view('dashboards.industry'))->middleware('auth');
Route::get('/dashboard/university', [UniversitySupervisorDashboardController::class, 'index'])->middleware('auth');
//Route::get('/dashboard/university', fn() => view('dashboards.university'))->middleware('auth');
Route::get('/dashboard/admin', fn() => view('dashboards.admin'))->middleware('auth');

//Routes for Daily Report
Route::middleware(['auth', 'ensure.student.profile'])->group(function () {
    Route::get('/daily-report/create', [DailyReportController::class, 'create'])->name('daily-report.create');
    Route::post('/daily-report', [DailyReportController::class, 'store'])->name('daily-report.store');
    // Route to generate PDF of Daily Reports (Logbook)
    Route::get('/daily-report/pdf', [DailyReportController::class, 'generatePdf'])->name('daily-report.pdf');
    Route::get('/daily-report/pdf-preview', [DailyReportController::class, 'previewPdf'])->name('daily-report.pdf_preview');
    Route::get('/daily-report/{id}/edit', [DailyReportController::class, 'edit'])->name('daily-report.edit');
    Route::put('/daily-report/{id}', [DailyReportController::class, 'update'])->name('daily-report.update');
    Route::delete('/daily-report/{id}', [DailyReportController::class, 'destroy'])->name('daily-report.destroy');
});

//Route for Report List
Route::middleware(['auth', 'ensure.student.profile'])->group(function () {
    Route::get('/daily-report/list', [DailyReportController::class, 'index'])->name('daily-report.list');
    Route::get('/daily-report/{id}', [DailyReportController::class, 'show'])->name('daily-report.show');
});

//Route for  Task
Route::middleware(['auth', 'ensure.student.profile'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{id}/update', [TaskController::class, 'update'])->name('tasks.update');
});

//Route for Student_profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::get('/internship/{id}', [InternshipController::class, 'show'])->name('internship.show');
    Route::get('/internship/{id}/edit', [InternshipController::class, 'edit'])->name('internship.edit');
    Route::post('/internship/{id}', [InternshipController::class, 'update'])->name('internship.update');
});

//Route for ASV dashboard
Route::middleware(['auth'])
    ->get('/supervisor/university/dashboard', [UniversitySupervisorDashboardController::class, 'index'])
    ->name('supervisor.university.dashboard');

//Route for ASV Student List view
Route::middleware(['auth'])->group(function () {
    Route::get('/supervisor/university/students', [UniversitySupervisorStudentController::class, 'index'])->name('supervisor.university.students');
    Route::get('/supervisor/university/student/{id}', [UniversitySupervisorStudentController::class, 'show'])->name('supervisor.university.student.show');
    Route::get('/supervisor/university/profile', [UniversitySupervisorProfileController::class, 'show'])->name('supervisor.university.profile');
    Route::post('/supervisor/university/profile', [UniversitySupervisorProfileController::class, 'update'])->name('supervisor.university.profile.update');
    Route::get('/supervisor/university/student/{id}/reports', [UniversitySupervisorStudentController::class, 'studentReports'])->name('supervisor.university.student.reports');
    Route::get('/supervisor/university/report/{id}', [UniversitySupervisorStudentController::class, 'showReport'])->name('supervisor.university.report.show');
    Route::post('/supervisor/university/report/{id}/feedback', [UniversitySupervisorStudentController::class, 'submitFeedback'])->name('supervisor.university.report.feedback');
    Route::delete('/supervisor/university/report/{id}/feedback', [UniversitySupervisorStudentController::class, 'deleteFeedback']);
});

//Route for admin manage accounts
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

//Route for admin profile
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('admin.profile');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.profile.changePassword');
    Route::get('/admin/assign-supervisor', [AdminUserController::class, 'assignSupervisorPage'])->name('admin.assign-supervisor');
    Route::post('/admin/assign-supervisor', [AdminUserController::class, 'storeAssignment'])->name('admin.assign-supervisor.store');
    Route::put('/admin/assign-supervisor/{internship}', [AdminUserController::class, 'updateAssignment'])->name('admin.assign-supervisor.update');
});

