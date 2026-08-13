<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $admin = $this->authenticatedUser();

        if (! $admin || $admin->role?->slug !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }

        return view('admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = $this->authenticatedUser();

        if (! $admin || $admin->role?->slug !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'mobile' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile ?? $admin->mobile,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = $this->authenticatedUser();

        if (! $admin || $admin->role?->slug !== 'admin') {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }

        if (! Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}