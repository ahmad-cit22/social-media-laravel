<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to access your profile.');
        }

        return view('pages.profile.index', ['user' => Auth::user()]);
    }

    public function show(User $user)
    {
        return view('pages.profile.others-profile', [
            'other_user' => $user,
            'user' => Auth::user(),
        ]);
    }

    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to edit your profile.');
        }

        return view('pages.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to update your profile.');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'alpha_num', 'unique:users,username,' . $user->id],
            'bio' => ['nullable', 'string', 'max:255'],
            'new_password' => ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', 'max:16', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'max:1024'],
        ], [
            'new_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                Storage::delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        if ($request->new_password !== null) {
            if (!Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Incorrect current password.');
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->bio = $validated['bio'] ?? null;

        $user->save();

        return redirect()->route('edit-profile')->with('success', 'Profile updated successfully.');
    }

}
