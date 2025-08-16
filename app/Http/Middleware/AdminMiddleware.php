<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access admin area.');
        }

        // Check if user has admin role (role_id = 1)
        $user = Auth::user();
        if (!$user->role_id || $user->role_id !== 1) {
            Auth::logout();
            
            return redirect()->route('login')->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
