<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user && $user->must_change_password) {
            if (!$request->is('force-change-password*')) {
                return redirect()->route('force.change.password');
            }
        }
        return $next($request);
    }
}
