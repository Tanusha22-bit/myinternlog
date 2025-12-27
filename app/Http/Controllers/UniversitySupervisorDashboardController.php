<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UniversitySupervisorDashboardController extends Controller
{
public function index(Request $request)
{
    $supervisor = \DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    $students = \DB::table('students')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->select('students.*', 'internships.status as internship_status')
        ->get();

    $totalStudents = $students->count();
    $activeInternships = $students->where('internship_status', 'active')->count();
    $completedInternships = $students->where('internship_status', 'completed')->count();

    $reportsThisMonth = \DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->whereMonth('daily_reports.created_at', now()->month)
        ->whereYear('daily_reports.created_at', now()->year)
        ->count();

    $recentReports = \DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->join('students', 'internships.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->whereNull('daily_reports.uni_feedback')
        ->orderByDesc('daily_reports.created_at')
        ->limit(5)
        ->select('daily_reports.*', 'users.name as student_name')
        ->get();

    // Show only a few students for summary
    $studentSummary = $students->take(3);

    return view('dashboards.university', compact(
        'totalStudents', 'activeInternships', 'completedInternships',
        'reportsThisMonth', 'recentReports', 'studentSummary'
    ));
}
}