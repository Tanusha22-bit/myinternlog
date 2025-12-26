<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UniversitySupervisorProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = DB::table('university_supervisors')->where('user_id', $request->user()->id)->first();
        return view('asv.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        DB::table('university_supervisors')->updateOrInsert(
            ['user_id' => $request->user()->id],
            $data + ['updated_at' => now()]
        );

        return redirect()->route('supervisor.university.profile')->with('success', 'Profile updated!');
    }
}