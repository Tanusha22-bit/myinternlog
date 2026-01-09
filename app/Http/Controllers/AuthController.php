<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('post.login.redirect');
    }
    return back()->withErrors(['email' => 'Invalid credentials.']);
}

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'security_question_1' => 'required|string|max:255',
            'security_answer_1' => 'required|string|max:255',
            'security_question_2' => 'required|string|max:255',
            'security_answer_2' => 'required|string|max:255',
            'security_question_3' => 'required|string|max:255',
            'security_answer_3' => 'required|string|max:255',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'role' => 'required|in:student,industry_sv,university_sv,admin',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'security_question_1' => $request->security_question_1,
            'security_answer_1' => Hash::make($request->security_answer_1),
            'security_question_2' => $request->security_question_2,
            'security_answer_2' => Hash::make($request->security_answer_2),
            'security_question_3' => $request->security_question_3,
            'security_answer_3' => Hash::make($request->security_answer_3),
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Automatically create supervisor records if needed
    if ($user->role === 'industry_sv') {
    \App\Models\IndustrySupervisor::create([
        'user_id' => $user->id,
        'position' => '',
        'company' => '',
        'phone' => '',
    ]);
    }
    if ($user->role === 'university_sv') {
    \App\Models\UniversitySupervisor::create([
        'user_id' => $user->id,
        'department' => '',
        'staff_id' => '',
        'phone' => '',
    ]);
    }

        $admins = \App\Models\User::where('role', 'admin')->get();
        Notification::send($admins, new NewUserRegistered($user));

        return redirect('/login')->with('status', 'Registration successful! Please log in.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
    
    public function handleForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user || !$user->security_question_1) {
            return back()->with('error', 'No account found or security questions not set.');
        }
        return view('auth.security-questions', ['user' => $user]);
    }

    public function showSecurityQuestions(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) return redirect()->route('forgot.password')->with('error', 'User not found.');
        return view('auth.security-questions', compact('user'));
    }

    public function checkSecurityQuestions(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer_1' => 'required|string',
            'answer_2' => 'required|string',
            'answer_3' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->with('error', 'User not found.');

        if (
            !Hash::check($request->answer_1, $user->security_answer_1) ||
            !Hash::check($request->answer_2, $user->security_answer_2) ||
            !Hash::check($request->answer_3, $user->security_answer_3)
        ) {
            return back()->with('error', 'One or more answers are incorrect.');
        }
        // All answers correct, redirect to reset password form
        return redirect()->route('reset.password.form', ['email' => $user->email]);
    }

    public function showResetPassword(Request $request)
    {
        $email = $request->email;
        return view('auth.reset-password', compact('email'));
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
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
        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->with('error', 'User not found.');
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('/login')->with('status', 'Password reset successful. Please login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForceChangePassword()
{
    $user = auth()->user();
    // You can pass the security questions list here
    $questions = [
        "What is your mother's maiden name?",
        "What was your first pet's name?",
        "What is your favorite food?",
        "What city were you born in?",
        "What is the name of your first school?"
    ];
    return view('auth.force-change-password', compact('user', 'questions'));
}

public function handleForceChangePassword(Request $request)
{
    $request->validate([
        'password' => [
            'required', 'confirmed', 'min:8',
            'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
        ],
        'security_question_1' => 'required|string|max:255|different:security_question_2,security_question_3',
        'security_answer_1' => 'required|string|max:255',
        'security_question_2' => 'required|string|max:255|different:security_question_1,security_question_3',
        'security_answer_2' => 'required|string|max:255',
        'security_question_3' => 'required|string|max:255|different:security_question_1,security_question_2',
        'security_answer_3' => 'required|string|max:255',
    ]);
    $user = auth()->user();
    $user->password = bcrypt($request->password);
    $user->security_question_1 = $request->security_question_1;
    $user->security_answer_1 = bcrypt($request->security_answer_1);
    $user->security_question_2 = $request->security_question_2;
    $user->security_answer_2 = bcrypt($request->security_answer_2);
    $user->security_question_3 = $request->security_question_3;
    $user->security_answer_3 = bcrypt($request->security_answer_3);
    $user->must_change_password = false;
    $user->save();
    return redirect('/dashboard')->with('success', 'Password and security questions updated!');
}
}