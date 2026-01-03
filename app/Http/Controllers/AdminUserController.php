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
        $users = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        })->paginate(10);

        return view('admin.manage-users', compact('users', 'search'));
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
            'role' => 'required|in:student,industry_sv,university_sv,admin',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'matric_id' => 'required_if:role,student',
            'student_phone' => 'required_if:role,student',
        ]);

        $user->update($validated);

        if ($user->role === 'student') {
            $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => $request->matric_id,
                    'phone' => $request->student_phone,
                ]
            );
        } elseif ($user->role === 'university_sv') {
            $user->universitySupervisor()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'staff_id' => $request->staff_id,
                    'department' => $request->department,
                    'phone' => $request->university_phone,
                ]
            );
        } elseif ($user->role === 'industry_sv') {
            $user->industrySupervisor()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'position' => $request->position,
                    'company' => $request->company,
                    'phone' => $request->industry_phone,
                ]
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function assignSupervisorPage()
    {
        $students = User::where('role', 'student')
            ->with([
                'student.internship.industrySupervisor.user',
                'student.internship.universitySupervisor.user'
            ])
            ->get();
        $industrySupervisors = User::where('role', 'industry_sv')->with('industrySupervisor')->get();
        $universitySupervisors = User::where('role', 'university_sv')->with('universitySupervisor')->get();

        return view('admin.assign-supervisor', compact('students', 'industrySupervisors', 'universitySupervisors'));
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
}