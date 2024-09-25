<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first to access your profile.');
        }

        $user = DB::table('users')->where('id', session('user_id'))->first();

        return view('pages.profile.index', ['user' => $user]);
    }

    public function edit()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first to edit your profile.');
        }

        $user = DB::table('users')->where('id', session('user_id'))->first();

        return view('pages.profile.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first to update your profile.');
        }

        $user = DB::table('users')->where('id', session('user_id'))->first();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users' . ',email,' . session('user_id')],
            'username' => ['required', 'string', 'max:255', 'unique:users' . ',username,' . session('user_id')],
            'bio' => ['nullable', 'string', 'max:255'],
            'new_password' => ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', 'max:16', 'confirmed'],
        ], [
            'new_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        $validated['bio'] = $validated['bio'] ? strip_tags($validated['bio']) : null;

        if ($validated['new_password'] !== null) {
            if (!Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Incorrect password provided.');
            }

            $validated['new_password'] = Hash::make($validated['new_password']);

            DB::table('users')
                ->where('id', session('user_id'))
                ->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'username' => $validated['username'],
                    'bio' => $validated['bio'],
                    'password' => $validated['new_password'],
                    'updated_at' => now(),
                ]);

            return redirect()->route('edit-profile')->with('success', 'Profile updated successfully.');
        }

        DB::table('users')
            ->where('id', session('user_id'))
            ->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'bio' => $validated['bio'],
                'updated_at' => now(),
            ]);

        return redirect()->route('edit-profile')->with('success', 'Profile updated successfully.');
    }
}
