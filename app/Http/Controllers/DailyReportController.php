<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\Internship;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DailyReportSubmitted;
use Illuminate\Support\Facades\Notification;

class DailyReportController extends Controller
{
    public function create()
{
    $user = Auth::user();
    $student = $user->student;
    $internship = Internship::where('student_id', $student->id)
        ->where('status', 'active')
        ->first();
    
    if (!$internship) {
        // Just pass nulls and let the view handle the message
        return view('daily_reports.create', [
            'internship' => null,
            'todaysLog' => null,
            'lastSubmission' => null,
            'totalWorkingDays' => 0,
            'submittedDays' => 0,
            'today' => now(),
        ]);
    }

    // Dates
    $today = Carbon::today();
    $start = Carbon::parse($internship->start_date);
    $end = Carbon::parse($internship->end_date);

    // 1. Today's log status
    $todaysLog = $internship
        ? $internship->dailyReports()->whereDate('report_date', $today)->first()
        : null;

    // 2. Last submission date
    $lastSubmission = $internship
        ? $internship->dailyReports()->orderByDesc('report_date')->first()
        : null;

    // 3. Progress bar: working days (Mon-Fri) between start and end, minus weekends
    $totalWorkingDays = 0;
    $period = Carbon::parse($start)->toPeriod($end);
    foreach ($period as $date) {
        if ($date->isWeekday()) $totalWorkingDays++;
    }

    // 4. Reports submitted (count of unique working days with a report)
    $submittedDays = $internship
        ? $internship->dailyReports()
            ->whereBetween('report_date', [$start, $end])
            ->whereRaw('WEEKDAY(report_date) < 5')
            ->distinct('report_date')
            ->count('report_date')
        : 0;

    return view('daily_reports.create', [
        'internship' => $internship,
        'todaysLog' => $todaysLog,
        'lastSubmission' => $lastSubmission,
        'totalWorkingDays' => $totalWorkingDays,
        'submittedDays' => $submittedDays,
        'today' => $today,
    ]);
    }

public function store(Request $request)
{
    $user = Auth::user();
    $student = $user->student;
    $internship = Internship::where('student_id', $student->id)
        ->where('status', 'active')
        ->first();

    $request->validate([
        'report_date' => 'required|date',
        'task' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,png,jpeg,jpg,doc,docx|max:10240',
    ]);

    $reportDate = Carbon::parse($request->report_date)->startOfDay();
    $today = Carbon::today();

    // 1. Only allow report for today
    if (!$reportDate->equalTo($today)) {
        return back()->withErrors(['report_date' => 'You can only submit a report for today ('. $today->format('d M Y') .').'])->withInput();
    }

    // 2. Only allow if today is within internship period
    $start = Carbon::parse($internship->start_date)->startOfDay();
    $end = Carbon::parse($internship->end_date)->endOfDay();
    if ($today->lt($start) || $today->gt($end)) {
        return back()->withErrors(['report_date' => 'You can only submit reports during your internship period ('. $start->format('d M Y') .' - '. $end->format('d M Y') .').'])->withInput();
    }

    // 3. Only allow on weekdays
    if ($today->isWeekend()) {
        return back()->withErrors(['report_date' => 'You cannot submit reports on weekends (Saturday or Sunday).'])->withInput();
    }

    // 4. Only one report per day
    $existing = DailyReport::where('internship_id', $internship->id)
        ->whereDate('report_date', $today)
        ->first();

    if ($existing) {
        return back()->withErrors(['report_date' => 'You have already submitted a report for today.'])->withInput();
    }

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('daily_reports', 'public');
    }

    $report = DailyReport::create([
        'internship_id' => $internship->id,
        'report_date' => $today,
        'task' => $request->task,
        'file' => $filePath,
        'status' => 'submitted',
    ]);

    $industrySupervisor = $internship->industrySupervisor->user;
    $universitySupervisor = $internship->universitySupervisor->user;
    Notification::send([$industrySupervisor, $universitySupervisor], new DailyReportSubmitted($report));

    return redirect()->route('daily-report.create')->with('success', 'Daily report submitted!');
}

    public function generatePdf()
    {
        $user = Auth::user();
        $student = $user->student;
        $student->load('user');
        $internship = \App\Models\Internship::where('student_id', $student->id)
        ->where('status', 'active')
        ->first();

        $reports = $internship
            ? $internship->dailyReports()->orderBy('report_date')->get()
            : collect();

        $pdf = Pdf::loadView('daily_reports.pdf', [
            'student' => $student,
            'internship' => $internship,
            'reports' => $reports,
        ]);

        return $pdf->download('logbook.pdf');
    }

    public function previewPdf()
    {
    $user = Auth::user();
    $student = $user->student;
    $student->load('user');
    $internship = Internship::where('student_id', $student->id)
        ->where('status', 'active')
        ->first();

    $reports = $internship
        ? $internship->dailyReports()->orderBy('report_date')->get()
        : collect();

    // Reuse the same data as for the PDF
    return view('daily_reports.pdf_preview', [
        'student' => $student,
        'internship' => $internship,
        'reports' => $reports,
    ]);
    }

public function index(Request $request)
{
    $user = Auth::user();
    $student = $user->student;
    $internship = \App\Models\Internship::where('student_id', $student->id)
        ->where('status', 'active')
        ->first();

    $status = $request->get('status'); // 'submitted' or 'reviewed'

    $reportsQuery = $internship
        ? $internship->dailyReports()->orderByDesc('report_date')
        : DailyReport::query()->whereRaw('0=1');

    if ($status) {
        $reportsQuery->where('status', $status);
    }

    $reports = $reportsQuery->paginate(10);

    // Add counts for all, submitted, reviewed
    $allCount = $internship ? $internship->dailyReports()->count() : 0;
    $submittedCount = $internship ? $internship->dailyReports()->where('status', 'submitted')->count() : 0;
    $reviewedCount = $internship ? $internship->dailyReports()->where('status', 'reviewed')->count() : 0;

    return view('daily_reports.index', [
        'reports' => $reports,
        'activeStatus' => $status,
        'allCount' => $allCount,
        'submittedCount' => $submittedCount,
        'reviewedCount' => $reviewedCount,
    ]);
}

   public function show($id)
   {
      $report = DailyReport::findOrFail($id);
      // Optionally, check if the user is allowed to view this report
      return view('daily_reports.show', compact('report'));
   }

   public function edit($id)
    {
        $report = DailyReport::findOrFail($id);
        // Check if the user is allowed to edit this report
        if ($report->status !== 'submitted') {
        return redirect()->route('daily-report.show', $report->id)
            ->with('error', 'You can only edit reports that are still submitted.');
        }
        return view('daily_reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $report = DailyReport::findOrFail($id);

    if ($report->status !== 'submitted') {
        return redirect()->route('daily-report.show', $report->id)
            ->with('error', 'You can only edit reports that are still submitted.');
    }

    $request->validate([
        'task' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,png,jpeg,jpg,doc,docx|max:10240', // 10MB
    ]);

    $report->task = $request->task;

    // Handle file deletion
    if ($request->has('delete_file') && $report->file) {
        \Storage::disk('public')->delete($report->file);
        $report->file = null;
    }

    // Handle file upload
    if ($request->hasFile('file')) {
        // Delete old file if exists
        if ($report->file) {
            \Storage::disk('public')->delete($report->file);
        }
        $report->file = $request->file('file')->store('daily_reports', 'public');
    }

    $report->save();

    return redirect()->route('daily-report.list')->with('success', 'Report updated successfully!');
    }

    public function destroy($id)
    {
        $report = DailyReport::findOrFail($id);
        $report->delete();
        return redirect()->route('daily-report.list')->with('success', 'Report deleted!');
    }
}