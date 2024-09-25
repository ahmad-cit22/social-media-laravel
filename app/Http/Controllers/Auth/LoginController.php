<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('user_id')) {
            return redirect()->route('home');
        }

        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (RateLimiter::tooManyAttempts('login:' . $request->ip() . '|' . $request->input('email'), 5)) {
            return redirect()->route('login')->with('error', 'Too many login attempts. Please try again later.');
        }

        $credentials = $request->only('email', 'password');

        $user = DB::table('users')->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit('login:' . $request->ip() . '|' . $request->input('email'), 60);

            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        session(['user_id' => $user->id]);

        RateLimiter::clear('login:' . $request->ip() . '|' . $request->input('email'));

        return redirect()->route('home')->with('success', 'Login successful.');
    }

    public function logout()
    {
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
