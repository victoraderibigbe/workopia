<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    // @desc Show register form
    // @route GET /login
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent fixation attacks
            $request->session()->regenerate();

            // Redirect to the intended or default route 
            return redirect()->intended(route('home'))->with('success', 'You are now logged in');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors(['email' => 'The provided credentials do not match with our records'])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // Logout the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/');
    }
}
