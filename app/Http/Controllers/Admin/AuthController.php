<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        // Validate the form inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        // Attempt to log the user in
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user has admin role (role_id = 1)
            if ($user->role_id !== 1) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['email' => 'You do not have admin access.'])
                    ->withInput();
            }

            // Regenerate session for security
            $request->session()->regenerate();

            // Redirect to admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // Login failed
        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput();
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
