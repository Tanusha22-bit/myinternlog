<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyReport;
use App\Models\Internship;
use App\Models\Announcement;
use App\Models\ImportantDate;

class IndustryDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $industrySupervisor = $user->industrySupervisor; // assuming relation: user->industrySupervisor

        // Get the internship for this supervisor (should be only one student)
        $internship = Internship::where('industry_sv_id', $industrySupervisor->id)->first();

        // Reports analytics
        $totalReports = 0;
        $reportsWithFeedback = 0;
        $lastReport = null;
        $recentReports = [];
        if ($internship) {
            $totalReports = DailyReport::where('internship_id', $internship->id)->count();
            $reportsWithFeedback = DailyReport::where('internship_id', $internship->id)
                ->whereNotNull('industry_feedback')->count();
            $lastReport = DailyReport::where('internship_id', $internship->id)
                ->orderByDesc('report_date')->first();
            $recentReports = DailyReport::where('internship_id', $internship->id)
                ->orderByDesc('report_date')->take(5)->get();
        }

        // Announcements & Important Dates for industry supervisors or all
        $announcements = Announcement::where(function($q){
                $q->where('role', 'industry_sv')->orWhere('role', 'all');
            })
            ->orderByDesc('created_at')->take(3)->get();

        $importantDates = ImportantDate::where(function($q){
                $q->where('role', 'industry_sv')->orWhere('role', 'all');
            })
            ->orderBy('date')->get();

        return view('dashboards.industry', compact(
            'internship',
            'totalReports',
            'reportsWithFeedback',
            'lastReport',
            'recentReports',
            'announcements',
            'importantDates'
        ));
    }
}