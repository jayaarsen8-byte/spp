<?php

namespace App\Http\Controllers;

use App\PasswordResetRequest as PasswordResetRequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only(['name', 'email']));

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.edit')->with('success', 'Password changed successfully.');
    }

    public function requestPasswordReset()
    {
        $existingRequest = PasswordResetRequestModel::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending password reset request.');
        }

        PasswordResetRequestModel::create([
            'user_id' => auth()->id(),
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Password reset request submitted. Awaiting owner approval.');
    }
}
