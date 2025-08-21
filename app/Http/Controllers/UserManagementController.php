<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['student', 'teacher'])->get();
        return view('settings.user-management', compact('users'));
    }

    public function create()
    {
        return view('settings.user-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student,accountant'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        // FIXED: Use the correct route name
        return redirect()->route('settings.user-management.index')
                        ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('settings.user-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,teacher,student,accountant'
        ]);

        $user->update($validated);

        // FIXED: Use the correct route name
        return redirect()->route('settings.user-management.index')
                        ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        // FIXED: Use the correct route name
        return redirect()->route('settings.user-management.index')
                        ->with('success', 'User deleted successfully.');
    }
}