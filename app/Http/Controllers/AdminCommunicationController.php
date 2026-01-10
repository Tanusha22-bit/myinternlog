<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\ImportantDate;
use App\Models\Document;

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

        if ($tab === 'document') {
            $documents = Document::orderByDesc('created_at')->paginate(10);
        } else {
            $documents = collect(); 
        }

        return view('admin.communications', compact('tab', 'role', 'announcements', 'dates', 'documents'));
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

    public function storeDocument(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,ppt,pptx,zip,rar,jpg,jpeg,png',
    ]);
    $path = $request->file('file')->store('documents', 'public');
    Document::create([
        'title' => $request->title,
        'file_path' => $path,
        'for_students' => true,
        'uploaded_by' => auth()->id(),
    ]);
    return back()->with('success', 'Document uploaded!');
}

public function updateDocument(Request $request, Document $document)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,ppt,pptx,zip,rar,jpg,jpeg,png',
    ]);
    $data = ['title' => $request->title];
    if ($request->hasFile('file')) {
        \Storage::disk('public')->delete($document->file_path);
        $data['file_path'] = $request->file('file')->store('documents', 'public');
    }
    $document->update($data);
    return back()->with('success', 'Document updated!');
}

public function destroyDocument(Document $document)
{
    \Storage::disk('public')->delete($document->file_path);
    $document->delete();
    return back()->with('success', 'Document deleted!');
}

}