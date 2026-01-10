<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Notifications\RemindFillCompany;

class UniversitySupervisorProgressController extends Controller
{

public function index(Request $request)
{
    $supervisor = \DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    // Filters
    $status = $request->query('status');
    $search = $request->query('search');

    // Students Query
    $studentsQuery = \DB::table('students')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->select(
            'students.*',
            'users.name as student_name',
            'internships.company_name',
            'internships.status as internship_status',
            'internships.id as internship_id',
            'internships.start_date as internship_start_date',
            'internships.end_date as internship_end_date'
        );

    if ($status) {
        $studentsQuery->where('internships.status', $status);
    }
    if ($search) {
        $studentsQuery->where(function($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
              ->orWhere('students.student_id', 'like', "%$search%");
        });
    }

    $students = $studentsQuery->get();

    // Calculate progress and feedback for each student
    $progressData = [];
    $totalProgress = 0;
    $feedbackGiven = 0;
    $totalReports = 0;

    foreach ($students as $student) {
        // Calculate expected reports (weekdays only)
        $totalExpectedReports = 0;
        if ($student->internship_start_date && $student->internship_end_date) {
            $start = \Carbon\Carbon::parse($student->internship_start_date);
            $end = \Carbon\Carbon::parse($student->internship_end_date);
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->copy()->addDay());
            foreach ($period as $date) {
                if (!in_array($date->format('N'), [6,7])) { // 6=Saturday, 7=Sunday
                    $totalExpectedReports++;
                }
            }
        }

        $totalReportsCount = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->count();

        $feedbackCount = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->whereNotNull('uni_feedback')
            ->count();

        $lastReport = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->orderByDesc('report_date')
            ->first();

        // Progress calculation
        $progress = ($totalExpectedReports > 0)
            ? min(100, round(($totalReportsCount / $totalExpectedReports) * 100))
            : 0;

        $progressData[] = [
            'student' => $student,
            'progress' => $progress,
            'last_report' => $lastReport ? $lastReport->report_date : '-',
            'feedback' => $feedbackCount,
            'total_reports' => $totalReportsCount,
            'expected_reports' => $totalExpectedReports,
        ];
        $totalProgress += $progress;
        $feedbackGiven += $feedbackCount;
        $totalReports += $totalReportsCount;
    }

    $avgProgress = count($students) ? round($totalProgress / count($students)) : 0;

    // For charts
    $statusCounts = [
        'active' => $students->where('internship_status', 'active')->count(),
        'completed' => $students->where('internship_status', 'completed')->count(),
        'inactive' => $students->where('internship_status', 'inactive')->count(),
    ];

    // Reports over time (last 6 months)
    $reportsOverTime = \DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->selectRaw('DATE_FORMAT(report_date, "%Y-%m") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return view('asv.student_progress', compact(
        'progressData', 'avgProgress', 'feedbackGiven', 'statusCounts', 'reportsOverTime', 'students'
    ));
}

public function downloadCsv(Request $request)
{
    $supervisor = \DB::table('university_supervisors')->where('user_id', auth()->id())->first();
    if (!$supervisor) abort(403, 'Supervisor not found.');

    // Use same filters as index
    $status = $request->query('status');
    $search = $request->query('search');

    $studentsQuery = \DB::table('students')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('internships', 'students.id', '=', 'internships.student_id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->select(
            'users.name as student_name',
            'students.student_id',
            'internships.company_name',
            'internships.status as internship_status',
            'internships.id as internship_id'
        );

    if ($status) {
        $studentsQuery->where('internships.status', $status);
    }
    if ($search) {
        $studentsQuery->where(function($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
              ->orWhere('students.student_id', 'like', "%$search%");
        });
    }

    $students = $studentsQuery->get();

    // Prepare CSV data
    $csvData = [];
    $csvData[] = ['Name', 'Matric ID', 'Company', 'Progress %', 'Status', 'Last Report', 'Feedback'];

    foreach ($students as $student) {
        $totalReportsCount = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->count();

        $feedbackCount = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->whereNotNull('uni_feedback')
            ->count();

        $lastReport = \DB::table('daily_reports')
            ->where('internship_id', $student->internship_id)
            ->orderByDesc('report_date')
            ->first();

        $progress = $totalReportsCount ? min(100, round(($totalReportsCount / 20) * 100)) : 0;

        $csvData[] = [
            $student->student_name,
            $student->student_id,
            $student->company_name,
            $progress . '%',
            ucfirst($student->internship_status),
            $lastReport ? $lastReport->report_date : '-',
            $feedbackCount ? 'Given' : 'Pending'
        ];
    }

    // Output CSV
    $filename = 'student_progress_' . now()->format('Ymd_His') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($csvData) {
        $file = fopen('php://output', 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function remindCompany($studentId)
{
    $student = \App\Models\Student::with('user')->findOrFail($studentId);
    $student->user->notify(new RemindFillCompany());

    return back()->with('success', 'Reminder notification sent to student!');
}
}
