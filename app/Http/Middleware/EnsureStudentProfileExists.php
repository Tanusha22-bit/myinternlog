<?php
// filepath: app/Http/Middleware/EnsureStudentProfileExists.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class EnsureStudentProfileExists
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $student = Student::where('user_id', Auth::id())->first();
            if (
                !$student &&
                !$request->routeIs('profile.show') &&
                !$request->routeIs('profile.update') &&
                !$request->routeIs('logout')
            ) {
                return redirect()->route('profile.show')->with('info', 'Please complete your profile before continuing.');
            }
        }
        return $next($request);
    }
}