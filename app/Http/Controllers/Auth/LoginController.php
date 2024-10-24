<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (RateLimiter::tooManyAttempts('login:' . $request->ip(), 5)) {
            return redirect()->route('login')->with('error', 'Too many login attempts. Please try again later.');
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            RateLimiter::hit('login:' . $request->ip(), 60);
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        RateLimiter::clear('login:' . $request->ip());

        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Login successful.');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
