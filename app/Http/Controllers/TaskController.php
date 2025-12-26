<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

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

        if ($status && $status !== 'all') {
        $tasksQuery->where('status', $status);
        }

        $tasks = $tasksQuery->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'activeStatus' => $status,
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

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }
}