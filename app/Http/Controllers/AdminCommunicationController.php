<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\ImportantDate;

class AdminCommunicationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'announcement');
        $role = $request->input('role', 'all');

        $announcementQuery = Announcement::query();
        $dateQuery = ImportantDate::query();

        if ($role !== 'all') {
            $announcementQuery->where('role', $role);
            $dateQuery->where('role', $role);
        }

        $announcements = $announcementQuery->orderByDesc('created_at')->paginate(10);
        $dates = $dateQuery->orderBy('date')->paginate(10);

        return view('admin.communications', compact('tab', 'role', 'announcements', 'dates'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'role' => 'nullable|string|in:student,university_sv,industry_sv,admin,all',
        ]);
        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'role' => $request->role ?? 'all',
            'created_by' => auth()->id(),
        ]);
        return back()->with('success', 'Announcement created!');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'role' => 'nullable|string|in:student,university_sv,industry_sv,admin,all',
        ]);
        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'role' => $request->role ?? 'all',
        ]);
        return back()->with('success', 'Announcement updated!');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted!');
    }

    public function storeDate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'role' => 'nullable|string|in:student,university_sv,industry_sv,admin,all',
        ]);
        ImportantDate::create([
            'title' => $request->title,
            'date' => $request->date,
            'role' => $request->role ?? 'all',
        ]);
        return back()->with('success', 'Important date created!');
    }

    public function updateDate(Request $request, ImportantDate $date)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'role' => 'nullable|string|in:student,university_sv,industry_sv,admin,all',
        ]);
        $date->update([
            'title' => $request->title,
            'date' => $request->date,
            'role' => $request->role ?? 'all',
        ]);
        return back()->with('success', 'Important date updated!');
    }

    public function destroyDate(ImportantDate $date)
    {
        $date->delete();
        return back()->with('success', 'Important date deleted!');
    }
}