<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;
use App\Models\Announcement;
use App\Models\ImportantDate;

class ProfileController extends Controller
{
    // Show the profile page
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            // Last login (if you have a last_login_at column, otherwise use updated_at)
            $lastLogin = $user->last_login_at ?? $user->updated_at;

            // Recent activity (last 5 announcements and important dates created/edited by this admin)
            $recentAnnouncements = Announcement::where('updated_at', $user->id)
                ->orderByDesc('updated_at')
                ->take(5)
                ->get();

            $recentDates = ImportantDate::where('updated_at', $user->id)
                ->orderByDesc('updated_at')
                ->take(5)
                ->get();

            return view('admin.profile', compact('user', 'lastLogin', 'recentAnnouncements', 'recentDates'));
        }

        // For students
        $student = Student::where('user_id', $user->id)->first();
        $internship = $student ? $student->internship : null;

        // Fetch activity overview data
        $totalReports = $internship ? $internship->dailyReports()->count() : 0;
        $reportsThisMonth = $internship ? $internship->dailyReports()
            ->whereMonth('report_date', now()->month)
            ->whereYear('report_date', now()->year)
            ->count() : 0;
        $totalTasksCompleted = $internship ? $internship->tasks()->where('status', 'completed')->count() : 0;
        $totalFeedback = $internship ? $internship->dailyReports()
            ->where(function($q){
                $q->whereNotNull('uni_feedback')->orWhereNotNull('industry_feedback');
            })->count() : 0;

        return view('profile.show', compact(
            'user', 'student', 'internship',
            'totalReports', 'reportsThisMonth', 'totalTasksCompleted', 'totalFeedback'
        ));
    }

    // Update profile details
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Handle profile picture upload
            if ($request->hasFile('profile_pic')) {
                // Delete old pic if exists
                if ($user->profile_pic) {
                    \Storage::disk('public')->delete($user->profile_pic);
                }
                $user->profile_pic = $request->file('profile_pic')->store('profile_pics', 'public');
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return back()->with('success', 'Profile updated!');
        }

        // For students
        $student = $user->student;

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'student_id' => 'required|string',
            'program' => 'required|string',
            'semester' => 'required|string',
            'phone' => 'required|string',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            // Delete old pic if exists
            if ($user->profile_pic) {
                \Storage::disk('public')->delete($user->profile_pic);
            }
            $user->profile_pic = $request->file('profile_pic')->store('profile_pics', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $student->student_id = $request->student_id;
        $student->program = $request->program;
        $student->semester = $request->semester;
        $student->phone = $request->phone;
        $student->save();

        return back()->with('success', 'Profile updated!');
    }

    // Change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed!');
    }
}