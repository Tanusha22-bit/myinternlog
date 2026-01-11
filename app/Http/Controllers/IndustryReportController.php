<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupervisorFeedbackGiven;

class IndustryReportController extends Controller
{
    
public function index(Request $request)
{
    $user = Auth::user();
    $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();
    $internship = DB::table('internships')->where('industry_sv_id', $industrySupervisor->id)->first();

    if (!$industrySupervisor) {
        abort(403, 'You are not an industry supervisor.');
    }

    $internship = DB::table('internships')->where('industry_sv_id', $industrySupervisor->id)->first();

    $status = $request->get('status', 'all');
    $date = $request->get('date');

    $baseQuery = $internship
        ? DailyReport::where('daily_reports.internship_id', $internship->id)
            ->leftJoin('internships', 'daily_reports.internship_id', '=', 'internships.id')
            ->leftJoin('students', 'internships.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'daily_reports.*',
                'users.name as student_name',
                'students.student_id as student_matric',
                'daily_reports.file'
            )
        : DailyReport::query()->whereRaw('0=1');

    // Use clone for each count
    $counts = [
        'all' => (clone $baseQuery)->count(),
        'pending' => (clone $baseQuery)->whereNull('daily_reports.industry_feedback')->count(),
        'given' => (clone $baseQuery)->whereNotNull('daily_reports.industry_feedback')->count(),
    ];

    // Now define $reportsQuery for the list
    $reportsQuery = (clone $baseQuery);
    if ($status === 'pending') {
        $reportsQuery->whereNull('daily_reports.industry_feedback');
    } elseif ($status === 'given') {
        $reportsQuery->whereNotNull('daily_reports.industry_feedback');
    }
    if ($date) {
        $reportsQuery->where('daily_reports.report_date', $date);
    }
    $reports = $reportsQuery->orderByDesc('daily_reports.report_date')->paginate(10)->withQueryString();

    return view('industry.reports', compact('reports', 'counts', 'status', 'date'));
}
    
public function feedback(Request $request, $reportId)
{
    $request->validate([
        'industry_feedback' => 'required|string|max:1000',
    ]);

    $user = Auth::user();
    $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();

    $report = \App\Models\DailyReport::with('internship.student.user')->findOrFail($reportId);

    // Always allow updating feedback
    $report->industry_feedback = $request->industry_feedback;
    $report->industry_feedback_by = $industrySupervisor->id;
    $report->industry_feedback_date = now();
    $report->save();

    $studentUser = $report->internship->student->user;
    $studentUser->notify(new SupervisorFeedbackGiven($request->industry_feedback, $report->id));

    return redirect()->route('industry.reports')->with('success', 'Feedback submitted!');
}
public function show($id)
{
    $report = DailyReport::leftJoin('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->leftJoin('students', 'internships.student_id', '=', 'students.id')
        ->leftJoin('users', 'students.user_id', '=', 'users.id')
        ->select(
            'daily_reports.*',
            'users.name as student_name',
            'students.student_id as student_matric',
            'daily_reports.file'
        )
        ->where('daily_reports.id', $id)
        ->firstOrFail();

    return view('industry.report_show', compact('report'));
}

public function deleteFeedback($reportId)
{
    $report = \App\Models\DailyReport::findOrFail($reportId);
    $report->industry_feedback = null;
    $report->industry_feedback_by = null;
    $report->industry_feedback_date = null;
    $report->save();

    return redirect()->route('industry.reports')->with('success', 'Feedback deleted successfully!');
}
}