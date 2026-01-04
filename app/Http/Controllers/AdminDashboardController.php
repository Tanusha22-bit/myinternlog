<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use App\Models\ImportantDate;
use App\Models\Internship;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{

public function index()
{
    $totalUsers = \App\Models\User::count();
    $totalStudents = \App\Models\User::where('role', 'student')->count();
    $totalIndustrySV = \App\Models\User::where('role', 'industry_sv')->count();
    $totalUniversitySV = \App\Models\User::where('role', 'university_sv')->count();
    $totalAdmins = \App\Models\User::where('role', 'admin')->count();
    $totalAnnouncements = \App\Models\Announcement::count();
    $totalDates = \App\Models\ImportantDate::count();
    $activeInternships = \App\Models\Internship::where('status', 'active')->count();
    $pendingAssignments = \App\Models\Internship::whereNull('industry_sv_id')->orWhereNull('university_sv_id')->count();

    // Registrations over time (monthly for current year)
    $registrationLabels = [];
    $registrationData = [];
    foreach (range(1, 12) as $month) {
        $registrationLabels[] = date('M', mktime(0,0,0,$month,1));
        $registrationData[] = \App\Models\User::whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->count();
    }

    return view('dashboards.admin', compact(
        'totalUsers', 'totalStudents', 'totalIndustrySV', 'totalUniversitySV', 'totalAdmins',
        'totalAnnouncements', 'totalDates', 'activeInternships', 'pendingAssignments',
        'registrationLabels', 'registrationData'
    ));
}

    // Download Internship Assignments as CSV
    public function downloadAssignmentsCsv()
    {
        $internships = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.id')
            ->join('users as student_users', 'students.user_id', '=', 'student_users.id')
            ->leftJoin('industry_supervisors', 'internships.industry_sv_id', '=', 'industry_supervisors.id')
            ->leftJoin('users as industry_users', 'industry_supervisors.user_id', '=', 'industry_users.id')
            ->leftJoin('university_supervisors', 'internships.university_sv_id', '=', 'university_supervisors.id')
            ->leftJoin('users as university_users', 'university_supervisors.user_id', '=', 'university_users.id')
            ->select(
                'student_users.name as student_name',
                'students.student_id',
                'internships.company_name',
                'internships.company_address',
                'internships.start_date',
                'internships.end_date',
                'internships.status',
                'industry_users.name as industry_supervisor',
                'university_users.name as university_supervisor'
            )
            ->get();

        $csvData = [];
        $csvData[] = [
            'Student Name', 'Matric ID', 'Company Name', 'Company Address',
            'Start Date', 'End Date', 'Status', 'Industry Supervisor', 'University Supervisor'
        ];

        foreach ($internships as $row) {
            $csvData[] = [
                $row->student_name,
                $row->student_id,
                $row->company_name,
                $row->company_address,
                $row->start_date,
                $row->end_date,
                ucfirst($row->status),
                $row->industry_supervisor,
                $row->university_supervisor
            ];
        }

        $filename = 'internship_assignments_' . now()->format('Ymd_His') . '.csv';
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

    // Download Analytics as CSV
    public function downloadAnalyticsCsv()
    {
        $csvData = [
            ['Metric', 'Value'],
            ['Total Users', User::count()],
            ['Total Students', User::where('role', 'student')->count()],
            ['Total Industry Supervisors', User::where('role', 'industry_sv')->count()],
            ['Total University Supervisors', User::where('role', 'university_sv')->count()],
            ['Total Admins', User::where('role', 'admin')->count()],
            ['Total Announcements', Announcement::count()],
            ['Total Important Dates', ImportantDate::count()],
            ['Active Internships', Internship::where('status', 'active')->count()],
            ['Pending Assignments', Internship::whereNull('industry_sv_id')->orWhereNull('university_sv_id')->count()],
        ];

        $filename = 'analytics_summary_' . now()->format('Ymd_His') . '.csv';
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
}