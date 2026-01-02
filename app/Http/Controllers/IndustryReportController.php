<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailyReport;

class IndustryReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();
        $internship = DB::table('internships')->where('industry_sv_id', $industrySupervisor->id)->first();

        $status = $request->get('status', 'all');
        $date = $request->get('date');

        // Map UI status to DB status
        $statusMap = [
            'all' => null,
            'pending' => 'submitted',
            'given' => 'reviewed',
        ];

        // Join with students and users for details
        $baseQuery = $internship
    ? \App\Models\DailyReport::where('daily_reports.internship_id', $internship->id)
        ->leftJoin('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->leftJoin('students', 'internships.student_id', '=', 'students.id')
        ->leftJoin('users', 'students.user_id', '=', 'users.id')
        ->select(
            'daily_reports.*',
            'users.name as student_name',
            'students.student_id as student_matric',
            'daily_reports.file'
        )
    : \App\Models\DailyReport::query()->whereRaw('0=1');

        // Use clone for each count
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->whereNull('daily_reports.industry_feedback')->count(),
            'given' => (clone $baseQuery)->whereNotNull('daily_reports.industry_feedback')->count(),
        ];

        // For filtering the list:
        if ($status === 'pending') {
            $reportsQuery->whereNull('daily_reports.industry_feedback');
        } elseif ($status === 'given') {
            $reportsQuery->whereNotNull('daily_reports.industry_feedback');
    }

        // Filter for list
        $reportsQuery = (clone $baseQuery);
        if ($status !== 'all' && isset($statusMap[$status])) {
            $reportsQuery->where('daily_reports.status', $statusMap[$status]);
        }
        if ($date) {
            $reportsQuery->where('daily_reports.report_date', $date);
        }

        // Pagination: 10 per page
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

        $report = DailyReport::findOrFail($reportId);
        // Only allow editing if not already reviewed
        if (is_null($report->industry_feedback)) {
        $report->industry_feedback = $request->industry_feedback;
        $report->industry_feedback_by = $industrySupervisor->id;
        $report->industry_feedback_date = now();
        $report->save();
    }
        return redirect()->route('industry.reports')->with('success', 'Feedback submitted!');
    }
}