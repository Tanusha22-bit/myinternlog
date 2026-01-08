<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UniversitySupervisorProfileController extends Controller
{

public function show()
{
    $user = auth()->user();
    $supervisor = \DB::table('university_supervisors')->where('user_id', $user->id)->first();

    // Activity stats
    $studentsCount = \DB::table('internships')->where('university_sv_id', $supervisor->id)->count();
    $feedbackGiven = \DB::table('daily_reports')
        ->join('internships', 'daily_reports.internship_id', '=', 'internships.id')
        ->where('internships.university_sv_id', $supervisor->id)
        ->whereNotNull('uni_feedback')
        ->count();
    $activeInternships = \DB::table('internships')->where('university_sv_id', $supervisor->id)->where('status', 'active')->count();
    $completedInternships = \DB::table('internships')->where('university_sv_id', $supervisor->id)->where('status', 'completed')->count();

    return view('asv.profile', compact('user', 'supervisor', 'studentsCount', 'feedbackGiven', 'activeInternships', 'completedInternships'));
}

public function update(Request $request)
{
    $user = auth()->user();
    $supervisor = \DB::table('university_supervisors')->where('user_id', $user->id)->first();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'staff_id' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'phone' => 'nullable|string|max:30',
        'profile_pic' => 'nullable|image|max:2048',
        'security_question_1' => 'nullable|string|max:255',
        'security_answer_1' => 'nullable|string|max:255',
        'security_question_2' => 'nullable|string|max:255',
        'security_answer_2' => 'nullable|string|max:255',
        'security_question_3' => 'nullable|string|max:255',
        'security_answer_3' => 'nullable|string|max:255',
    ]);

    // Handle profile picture
    if ($request->hasFile('profile_pic')) {
        $path = $request->file('profile_pic')->store('profile_pics', 'public');
        \DB::table('users')->where('id', $user->id)->update(['profile_pic' => $path]);
    }
            if ($request->filled('security_question_1') && $request->filled('security_answer_1')) {
                $user->security_question_1 = $request->security_question_1;
                $user->security_answer_1 = \Hash::make($request->security_answer_1);
            }
            if ($request->filled('security_question_2') && $request->filled('security_answer_2')) {
                $user->security_question_2 = $request->security_question_2;
                $user->security_answer_2 = \Hash::make($request->security_answer_2);
            }
            if ($request->filled('security_question_3') && $request->filled('security_answer_3')) {
                $user->security_question_3 = $request->security_question_3;
                $user->security_answer_3 = \Hash::make($request->security_answer_3);
            }

    // Update user info
    \DB::table('users')->where('id', $user->id)->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);
    // Update supervisor info
    \DB::table('university_supervisors')->where('id', $supervisor->id)->update([
        'staff_id' => $request->staff_id,
        'department' => $request->department,
        'phone' => $request->phone,
    ]);

    return back()->with('success', 'Profile updated successfully!');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    \DB::table('users')->where('id', $user->id)->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Password updated successfully!');
}
}