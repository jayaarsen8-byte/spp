<?php

namespace App\Http\Controllers;

use App\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:owner');
    }

    public function index()
    {
        $requests = PasswordResetRequest::with('user', 'reviewer')
            ->orderByDesc('requested_at')
            ->paginate(50);

        return view('password-resets.index', ['requests' => $requests]);
    }

    public function approve(Request $request, PasswordResetRequest $resetRequest)
    {
        $request->validate(['new_password' => 'required|min:6|confirmed']);

        $resetRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $resetRequest->user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('password-resets.index')->with('success', 'Password reset approved.');
    }

    public function reject(Request $request, PasswordResetRequest $resetRequest)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $resetRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('password-resets.index')->with('success', 'Password reset request rejected.');
    }
}
