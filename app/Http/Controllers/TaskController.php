<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssigned;


class TaskController extends Controller
{
    // List all tasks for the student's internship
public function index(Request $request)
{
    $user = Auth::user();
    $student = $user->student;
    $internship = $student->internship; // adjust if needed

    $status = $request->get('status'); // 'pending', 'in_progress', 'completed'

    $tasksQuery = $internship
        ? $internship->tasks()->orderBy('due_date')
        : \App\Models\Task::query()->whereRaw('0=1');

    // Build base query for counts
    $baseQuery = $internship
        ? \App\Models\Task::where('internship_id', $internship->id)
        : \App\Models\Task::query()->whereRaw('0=1');

    $counts = [
        'all' => (clone $baseQuery)->count(),
        'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
        'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
    ];

    if ($status && $status !== 'all') {
        $tasksQuery->where('status', $status);
    }

    $tasks = $tasksQuery->paginate(10);

    return view('tasks.index', [
        'tasks' => $tasks,
        'activeStatus' => $status,
        'counts' => $counts,
    ]);
}

    // Show a single task
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    // Update task status
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Optionally, check if the current student is allowed to update this task

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'student_note' => 'nullable|string',
        ]);

        $task->status = $request->status;
        $task->student_note = $request->student_note;
        $task->save();

        $industrySupervisorUser = $task->internship->industrySupervisor->user ?? null;

        if ($industrySupervisorUser) {
        $industrySupervisorUser->notify(new \App\Notifications\TaskStatusUpdated($task));
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function industryIndex(Request $request)
{
    $user = Auth::user();
    $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();
    $internship = DB::table('internships')->where('industry_sv_id', $industrySupervisor->id)->first();

    $startDate = $internship ? $internship->start_date : null;
    $endDate = $internship ? $internship->end_date : null;

    $status = $request->get('status', 'all');
    $baseQuery = $internship
        ? Task::where('internship_id', $internship->id)
        : Task::query()->whereRaw('0=1');

    // Use clone for each count to avoid query pollution
    $counts = [
        'all' => (clone $baseQuery)->count(),
        'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
        'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
    ];

    // Now apply filter for the actual list
    $tasksQuery = (clone $baseQuery);
    if ($status !== 'all') {
        $tasksQuery->where('status', $status);
    }

    // Search functionality
    $search = $request->get('search');
    if ($search) {
        $tasksQuery->where(function($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    $tasks = $tasksQuery->orderBy('due_date')->paginate(10);

    return view('industry.tasks', compact('tasks', 'counts', 'status', 'startDate', 'endDate'));
    // Search functionality
    $search = $request->get('search');
if ($search) {
    $tasksQuery->where(function($q) use ($search) {
        $q->where('title', 'like', "%$search%")
          ->orWhere('description', 'like', "%$search%");
    });
}
}

public function industryStore(Request $request)
{
    $user = Auth::user();
    $industrySupervisor = DB::table('industry_supervisors')->where('user_id', $user->id)->first();
    $internship = DB::table('internships')->where('industry_sv_id', $industrySupervisor->id)->first();

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => "nullable|date|after_or_equal:$internship->start_date|before_or_equal:$internship->end_date",
    ]);

    $task = Task::create([
        'internship_id' => $internship->id,
        'title' => $request->title,
        'description' => $request->description,
        'due_date' => $request->due_date,
        'status' => 'pending',
    ]);

    $internshipModel = \App\Models\Internship::find($internship->id);
    $student = $internshipModel->student;
    $user = $student->user;
    $user->notify(new TaskAssigned($task));

    return redirect()->route('industry.tasks')->with('success', 'Task assigned!');
}

public function industryEdit(Task $task)
{
    return view('industry.task_edit', compact('task'));
}

public function industryUpdate(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
    ]);

    $task->update($request->only('title', 'description', 'due_date'));

    return redirect()->route('industry.tasks')->with('success', 'Task updated!');
}

public function industryDestroy(Task $task)
{
    $task->delete();
    return redirect()->route('industry.tasks')->with('success', 'Task deleted!');
}
}