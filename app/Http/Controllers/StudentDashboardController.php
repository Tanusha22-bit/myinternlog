<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class StudentDashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $internship = $user->student->internship ?? null;

    $reportsThisMonth = $internship ? $internship->dailyReports()->whereMonth('report_date', now()->month)->count() : 0;
    $reportsOverall = $internship ? $internship->dailyReports()->count() : 0;
    $tasksPending = $internship ? $internship->tasks()->where('status', 'pending')->count() : 0;
    $tasksInProgress = $internship ? $internship->tasks()->where('status', 'in_progress')->count() : 0;
    $tasksCompleted = $internship ? $internship->tasks()->where('status', 'completed')->count() : 0;

    $start = $internship ? \Carbon\Carbon::parse($internship->start_date) : now();
    $end = $internship ? \Carbon\Carbon::parse($internship->end_date) : now();
    $totalWeeks = $start->diffInWeeks($end) + 1;
    $currentWeek = $start->diffInWeeks(now()) + 1;
    $progressPercent = min(100, max(0, intval(($currentWeek / $totalWeeks) * 100)));

    // Important Dates 
    $importantDates = \DB::table('important_dates')
        ->where('for_students', true)
        ->orderBy('date')
        ->get();

    // Downloadable Documents
    $documents = \DB::table('documents')
        ->where('for_students', true)
        ->orderBy('created_at', 'desc')
        ->get();

    // Supervisors
    $industrySupervisor = $internship ? $internship->industrySupervisor : null;
    $universitySupervisor = $internship ? $internship->universitySupervisor : null;

    // Activity Feed 
    $activities = $internship
        ? \DB::table('activities')->where('student_id', $user->student->id)->orderBy('created_at', 'desc')->limit(10)->get()
        : collect();

    // Announcements 
    $announcements = \DB::table('announcements')
        ->where('for_students', true)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('dashboards.student', compact(
        'reportsThisMonth', 'reportsOverall',
        'tasksPending', 'tasksInProgress', 'tasksCompleted',
        'progressPercent', 'currentWeek', 'totalWeeks',
        'importantDates', 'documents',
        'industrySupervisor', 'universitySupervisor',
        'activities', 'announcements'
    ));
}
}
