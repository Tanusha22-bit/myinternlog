<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            // Redirect based on role
            switch (auth()->user()->role) {
                case 'student': return redirect('/dashboard');
                case 'industry_sv': return redirect('/dashboard/industry');
                case 'university_sv': return redirect('/dashboard/university');
                case 'admin': return redirect('/dashboard/admin');
                default: return redirect('/');
            }
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:student,industry_sv,university_sv,admin',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        Auth::login($user);
        switch ($user->role) {
            case 'student': return redirect('/dashboard');
            case 'industry_sv': return redirect('/dashboard/industry');
            case 'university_sv': return redirect('/dashboard/university');
            case 'admin': return redirect('/dashboard/admin');
            default: return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}