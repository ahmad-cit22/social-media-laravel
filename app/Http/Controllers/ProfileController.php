<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
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
        return view('pages.profile.index', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        if (!$this->isAuthorized($user)) {
            return back()->with('error', 'Sorry! You are not authorized for this action.');
        }

        return view('pages.profile.edit', ['user' => $user]);
    }

    public function update(ProfileUpdateRequest $request, User $user)
    {
        if (!$this->isAuthorized($user)) {
            return back()->with('error', 'Sorry! You are not authorized for this action.');
        }

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
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

        return redirect()->route('profile.edit', $user->id)->with('success', 'Profile updated successfully.');
    }

    protected function isAuthorized(User $user)
    {
        if (!Auth::check() || Auth::id() !== $user->id) {
            return false;
        }

        return true;
    }

}
