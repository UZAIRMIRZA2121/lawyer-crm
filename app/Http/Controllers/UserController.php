<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
                'username' => 'nullable|string|unique:users,username,' . $user->id,
                'name' => 'nullable|string',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
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
                $file = $request->file('profile_img');
                if ($file->isValid()) {
                    // file is valid, try to store
                    $data['profile_img'] = $file->store('profile_imgs', 'public');
                } else {
                    Log::error('Uploaded profile_img is invalid');
                }
            } else {
                Log::info('No profile_img file uploaded');
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





    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'profile_img' => 'nullable|image|max:2048',
            'qualification' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ]);

        // Basic info
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->qualification = $request->qualification;
        $user->contact = $request->contact;
        $user->facebook = $request->facebook;
        $user->twitter = $request->twitter;
        $user->linkedin = $request->linkedin;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Profile image upload
        if ($request->hasFile('profile_img')) {
            $path = $request->file('profile_img')->store('profile_images', 'public');
            $user->profile_img = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
