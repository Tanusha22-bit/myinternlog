<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UniversitySupervisorStudentController extends Controller
{
public function index(Request $request)
{
    $supervisor = DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');
    $supervisorId = $supervisor->id;

    $statusFilter = $request->query('status');

    $studentsQuery = DB::table('students')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisorId)
        ->select(
            'students.*',
            'users.name as student_name',
            'internships.company_name',
            'internships.status'
        );

    if ($statusFilter) {
        if ($statusFilter === 'active') {
            $studentsQuery->where('internships.status', 'active');
        } elseif ($statusFilter === 'inactive') {
            $studentsQuery->where('internships.status', '!=', 'active');
        }
    }

    $students = $studentsQuery->paginate(10); // PAGINATION

    // Counts for filter cards (optional)
    $allCount = DB::table('students')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisorId)
        ->count();

    $activeCount = DB::table('students')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisorId)
        ->where('internships.status', 'active')
        ->count();

    $inactiveCount = DB::table('students')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisorId)
        ->where('internships.status', '!=', 'active')
        ->count();

    return view('asv.studentlist', compact('students', 'allCount', 'activeCount', 'inactiveCount', 'statusFilter'));
}

    public function show($id)
    {
    // Get student and internship details, including user name
        $student = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select('students.*', 'users.name as student_name')
            ->first();

        $internship = DB::table('internships')
            ->where('student_id', $id)
            ->first();

        return response()->json([
            'student' => $student,
            'internship' => $internship,
        ]);
    }

    public function reports(Request $request)
{
    $supervisor = DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    $reports = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->join('students', 'internships.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->select(
            'daily_reports.*',
            'users.name as student_name',
            'students.student_id as matric_id'
        )
        ->orderByDesc('daily_reports.report_date')
        ->get();

    return view('asv.reports', compact('reports'));
}

// Show a single report for feedback
public function showReport($id)
{
    $report = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->join('students', 'internships.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('daily_reports.id', $id)
        ->select(
            'daily_reports.*',
            'users.name as student_name',
            'students.student_id as matric_id',
            'students.id as student_id' // <-- add this line
        )
        ->first();

    return view('asv.report_detail', compact('report'));
}

// Handle feedback submission
public function submitFeedback(Request $request, $id)
{
    $request->validate([
        'uni_feedback' => 'required|string|max:2000',
    ]);

    $supervisor = DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    // Fetch the student_id for redirect
    $report = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('daily_reports.id', $id)
        ->select('internships.student_id')
        ->first();

    DB::table('daily_reports')->where('id', $id)->update([
        'uni_feedback' => $request->uni_feedback,
        'uni_feedback_by' => $supervisor->id,
        'uni_feedback_date' => now(),
        'status' => 'reviewed',
    ]);

    return redirect()->route('supervisor.university.report.show', $id)
    ->with('success', 'Feedback submitted successfully!');
}

public function studentReports(Request $request, $studentId)
{
    $supervisor = DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    $student = DB::table('students')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('students.id', $studentId)
        ->select('students.*', 'users.name as student_name')
        ->first();

    $query = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.student_id', $studentId)
        ->select('daily_reports.*');

    // Search by date
    if ($request->filled('date')) {
        $query->where('daily_reports.report_date', $request->date);
    }

    // Filter by feedback status
    $feedback = $request->query('feedback');
    if ($feedback === 'given') {
        $query->whereNotNull('daily_reports.uni_feedback');
    } elseif ($feedback === 'notyet') {
        $query->whereNull('daily_reports.uni_feedback');
    }

    $reports = $query->orderByDesc('daily_reports.report_date')->paginate(10);

    // Totals for cards
    $allCount = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.student_id', $studentId)
        ->count();

    $givenCount = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.student_id', $studentId)
        ->whereNotNull('daily_reports.uni_feedback')
        ->count();

    $notYetCount = DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.student_id', $studentId)
        ->whereNull('daily_reports.uni_feedback')
        ->count();

    return view('asv.student_reports', compact('student', 'reports', 'allCount', 'givenCount', 'notYetCount', 'feedback'));
}

public function deleteFeedback($id)
{
    DB::table('daily_reports')->where('id', $id)->update([
        'uni_feedback' => null,
        'uni_feedback_by' => null,
        'uni_feedback_date' => null,
        'status' => 'submitted',
    ]);
    return response()->json(['success' => true]);
}

}