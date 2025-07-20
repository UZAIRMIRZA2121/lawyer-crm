<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'team')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string',
            'qualification' => 'nullable|string',
            'contact' => 'nullable|string',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'profile_img' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['profile_img', 'password']);

        // Hash password
        $data['password'] = Hash::make($request->password);

        // Set default role if not provided
        $data['role'] = 'team';

        // Handle image upload
        if ($request->hasFile('profile_img')) {
            $data['profile_img'] = $request->file('profile_img')->store('profile_imgs', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }



    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users,username,' . $user->id,
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'role' => 'nullable|string',
                'qualification' => 'nullable|string',
                'contact' => 'nullable|string',
                'facebook' => 'nullable|url',
                'twitter' => 'nullable|url',
                'linkedin' => 'nullable|url',
                'profile_img' => 'nullable|image|max:2048',
            ]);

            $data = $request->except(['profile_img', 'password']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['role'] = $request->input('role', 'team');

            if ($request->hasFile('profile_img')) {
                if ($user->profile_img && \Storage::disk('public')->exists($user->profile_img)) {
                    \Storage::disk('public')->delete($user->profile_img);
                }
                $data['profile_img'] = $request->file('profile_img')->store('profile_imgs', 'public');
            }

            $user->update($data);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return back()->withErrors('Failed to update user. Please try again.');
        }
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
