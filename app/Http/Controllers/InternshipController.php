<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Internship;

class InternshipController extends Controller
{
    public function show()
    {
        $student = auth()->user()->student;
        $internship = Internship::with([
            'student.user',
            'industrySupervisor.user',
            'universitySupervisor.user',
            'tasks',
            'dailyReports'
        ])->where('student_id', $student->id)->first();

        return view('internship.show', compact('internship'));
    }

    public function edit()
    {
        $student = auth()->user()->student;
        $internship = Internship::where('student_id', $student->id)->firstOrFail();
        return view('internship.edit', compact('internship'));
    }

    public function update(Request $request)
    {
        $student = auth()->user()->student;
        $internship = Internship::where('student_id', $student->id)->firstOrFail();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'offer_letter' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ]);

        if ($request->hasFile('offer_letter')) {
            // Delete old file if exists
            if ($internship->offer_letter) {
                \Storage::disk('public')->delete($internship->offer_letter);
            }
            $internship->offer_letter = $request->file('offer_letter')->store('offer_letters', 'public');
        }

        $internship->company_name = $request->company_name;
        $internship->company_address = $request->company_address;
        $internship->start_date = $request->start_date;
        $internship->end_date = $request->end_date;
        $internship->save();

        return redirect()->route('internship.show')->with('success', 'Internship details updated!');
    }
}