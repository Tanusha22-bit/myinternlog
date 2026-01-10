<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Internship;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $role = $request->input('role');

    $users = User::when($role && $role !== 'all', function ($query) use ($role) {
            $query->where('role', $role);
        })
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        })
        ->paginate(10);

    $counts = [
        'all' => User::count(),
        'student' => User::where('role', 'student')->count(),
        'university_sv' => User::where('role', 'university_sv')->count(),
        'industry_sv' => User::where('role', 'industry_sv')->count(),
        'admin' => User::where('role', 'admin')->count(),
    ];

    return view('admin.manage-users', compact('users', 'search', 'role', 'counts'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:student,industry_sv,university_sv,admin',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'matric_id' => 'required_if:role,student',
            'student_phone' => 'required_if:role,student',
            'staff_id' => 'required_if:role,university_sv',
            'department' => 'required_if:role,university_sv',
            'university_phone' => 'required_if:role,university_sv',
            'position' => 'required_if:role,industry_sv',
            'company' => 'required_if:role,industry_sv',
            'industry_phone' => 'required_if:role,industry_sv',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'password' => bcrypt($request->password),
            'must_change_password' => true,
        ]);

        if ($user->role === 'student') {
            $user->student()->create([
                'student_id' => $request->matric_id,
                'phone' => $request->student_phone,
                'program' => $request->program,
                'semester' => $request->semester,
            ]);
        } elseif ($user->role === 'university_sv') {
            $user->universitySupervisor()->create([
                'staff_id' => $request->staff_id,
                'department' => $request->department,
                'phone' => $request->university_phone,
            ]);
        } elseif ($user->role === 'industry_sv') {
            $user->industrySupervisor()->create([
                'position' => $request->position,
                'company' => $request->company,
                'phone' => $request->industry_phone,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'student_id' => 'required_if:role,student',
        'student_phone' => 'required_if:role,student',
        'industry_sv_id' => 'required|exists:industry_supervisors,id',
        'university_sv_id' => 'required|exists:university_supervisors,id',
        'status' => 'required|in:active,completed,terminated',
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
    ]);

    if ($user->role === 'student') {
        $user->student()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'student_id' => $request->student_id,
                'phone' => $request->student_phone,
            ]
        );

        // Update internship
        $internship = Internship::where('student_id', $user->student->id)->first();
        if ($internship) {
            $internship->update([
                'industry_sv_id' => $request->industry_sv_id,
                'university_sv_id' => $request->university_sv_id,
                'status' => $request->status,
            ]);
        }
    }

    return back()->with('success', 'User updated successfully!');
}

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

public function assignSupervisorPage(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');

   $studentsQuery = User::where('role', 'student')
    ->with([
        'student.internship.industrySupervisor.user',
        'student.internship.universitySupervisor.user'
    ])
    ->where(function ($q) {
        $q->whereHas('student.internship', function ($q2) {
            $q2->whereNotIn('status', ['completed', 'terminated']);
        })
        ->orWhereDoesntHave('student.internship');
    });

    if ($search) {
        $studentsQuery->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('student_id', 'like', "%$search%");
                })
                ->orWhereHas('student.internship.industrySupervisor.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('student.internship.universitySupervisor.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        });
    }

    if ($status === 'active') {
    $studentsQuery->whereHas('student.internship', function ($q) {
        $q->where('status', 'active');
    });
    } elseif ($status === 'pending') {
    $studentsQuery->whereDoesntHave('student.internship');
    }

    $students = $studentsQuery->paginate(10);

    // Counts for cards
    $allCount = User::where('role', 'student')->count();
    $activeCount = User::where('role', 'student')->whereHas('student.internship', function($q){ $q->where('status', 'active'); })->count();
    $pendingCount = User::where('role', 'student')->whereDoesntHave('student.internship')->count();

    $industrySupervisors = User::where('role', 'industry_sv')->with('industrySupervisor')->get();
    $universitySupervisors = User::where('role', 'university_sv')->with('universitySupervisor')->get();

    return view('admin.assign-supervisor', compact(
        'students', 'industrySupervisors', 'universitySupervisors', 'search', 'status',
        'allCount', 'activeCount', 'pendingCount'
    ));
}

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'industry_sv_id' => 'required|exists:industry_supervisors,id',
            'university_sv_id' => 'required|exists:university_supervisors,id',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,completed,terminated',
        ]);
        Internship::updateOrCreate(
            ['student_id' => $request->student_id],
            $request->only([
                'industry_sv_id',
                'university_sv_id',
                'company_name',
                'company_address',
                'start_date',
                'end_date',
                'status',
            ])
        );
        return back()->with('success', 'Supervisor assigned successfully!');
    }

    public function updateAssignment(Request $request, Internship $internship)
    {
        $request->validate([
            'industry_sv_id' => 'required|exists:industry_supervisors,id',
            'university_sv_id' => 'required|exists:university_supervisors,id',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,completed,terminated',
        ]);
        $internship->update($request->only([
            'industry_sv_id',
            'university_sv_id',
            'company_name',
            'company_address',
            'start_date',
            'end_date',
            'status',
        ]));
        return back()->with('success', 'Assignment updated successfully!');
    }

    public function editAssignment($studentId)
    {
    $student = User::where('role', 'student')
        ->with([
            'student.internship.industrySupervisor.user',
            'student.internship.universitySupervisor.user'
        ])
        ->findOrFail($studentId);

    $industrySupervisors = User::where('role', 'industry_sv')->with('industrySupervisor')->get();
    $universitySupervisors = User::where('role', 'university_sv')->with('universitySupervisor')->get();

    return view('admin.edit-assignment', compact('student', 'industrySupervisors', 'universitySupervisors'));
    }

public function historyPage(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status'); // <-- add this

    $studentsQuery = User::where('role', 'student')
        ->with([
            'student.internship.industrySupervisor.user',
            'student.internship.universitySupervisor.user'
        ])
        ->whereHas('student.internship', function ($q) use ($status) {
            if ($status === 'completed') {
                $q->where('status', 'completed');
            } elseif ($status === 'terminated') {
                $q->where('status', 'terminated');
            } else {
                $q->whereIn('status', ['completed', 'terminated']);
            }
        });

    if ($search) {
        $studentsQuery->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('student_id', 'like', "%$search%");
                })
                ->orWhereHas('student.internship.industrySupervisor.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('student.internship.universitySupervisor.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        });
    }

    $students = $studentsQuery->paginate(10);

    // For cards (always count all, completed, terminated)
    $allCount = User::where('role', 'student')
        ->whereHas('student.internship', function ($q) {
            $q->whereIn('status', ['completed', 'terminated']);
        })->count();
    $completedCount = User::where('role', 'student')
        ->whereHas('student.internship', function ($q) {
            $q->where('status', 'completed');
        })->count();
    $terminatedCount = User::where('role', 'student')
        ->whereHas('student.internship', function ($q) {
            $q->where('status', 'terminated');
        })->count();

    $industrySupervisors = User::where('role', 'industry_sv')->with('industrySupervisor')->get();
    $universitySupervisors = User::where('role', 'university_sv')->with('universitySupervisor')->get();

    return view('admin.history', compact(
        'students', 'search', 'industrySupervisors', 'universitySupervisors',
        'allCount', 'completedCount', 'terminatedCount', 'status'
    ));
}
}