<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndustryProfileController extends Controller
{

public function show()
{
    $user = Auth::user();
    $industrySupervisor = $user->industrySupervisor;
    return view('industry.profile', compact('user', 'industrySupervisor'));
}

public function update(Request $request)
{
    $user = Auth::user();
    $industrySupervisor = $user->industrySupervisor;

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'profile_pic' => 'nullable|image|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->hasFile('profile_pic')) {
        $path = $request->file('profile_pic')->store('profile_pics', 'public');
        $user->profile_pic = $path;
    }
    $user->save();

    $industrySupervisor->phone = $request->phone; // <-- Save phone here
    $industrySupervisor->company = $request->company;
    $industrySupervisor->save();

    return back()->with('success', 'Profile updated successfully!');
}

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}