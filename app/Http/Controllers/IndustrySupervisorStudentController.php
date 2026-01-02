<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustrySupervisorStudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();

        $student = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('university_supervisors', 'internships.university_sv_id', '=', 'university_supervisors.id')
            ->leftJoin('users as uni_user', 'university_supervisors.user_id', '=', 'uni_user.id')
            ->where('internships.industry_sv_id', $industrySupervisor->id)
            ->select(
                'students.*',
                'users.name as student_name',
                'users.email as email',
                'users.profile_pic',
                'internships.company_name',
                'internships.status as internship_status',
                'internships.start_date',
                'internships.end_date',
                'university_supervisors.department',
                'university_supervisors.phone as uni_phone',
                'uni_user.name as uni_name',
                'uni_user.email as uni_email'
            )
            ->first();

    // Pass supervisor details as an object for easy access
        if ($student) {
            $student->university_sv = (object)[
                'name' => $student->uni_name,
                'email' => $student->uni_email,
                'department' => $student->department,
                'phone' => $student->uni_phone,
            ];
        }

        return view('industry.students', compact('student'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();

        $student = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('internships.industry_sv_id', $industrySupervisor->id)
            ->where('students.id', $id)
            ->select(
                'students.*',
                'users.name as student_name',
                'internships.company_name',
                'internships.status as internship_status',
                'internships.start_date',
                'internships.end_date'
            )
            ->first();

        if (!$student) {
            abort(404);
        }

        return view('industry.student_show', compact('student'));
    }
}
