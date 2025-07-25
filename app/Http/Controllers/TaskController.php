<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\HasFactory;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $tasks = Task::with('user')->latest()->get();
        } else {
            $tasks = Task::with('user')->where('user_id', $user->id)->latest()->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'team')->get();
        } else {
            $users = collect([$user]); // only allow self-creation for team
        }

        return view('tasks.create', compact('users'));
    }

public function store(Request $request)
{
    $authUser = auth()->user();

    $request->validate([
        'user_ids'   => 'required|array',
        'user_ids.*' => 'exists:users,id',
        'task'       => 'required|string',
        'priority'   => 'required|in:normal,urgent',
        'submit_date'=> 'required|date',
        'status'     => 'required|in:pending,working,completed',
    ]);

    foreach ($request->user_ids as $userId) {
        // Prevent team users from assigning tasks to others
        if ($authUser->role !== 'admin' && $authUser->id != $userId) {
            continue; // skip unauthorized assignment
        }

        Task::create([
            'user_id'     => $userId,
            'task'        => $request->task,
            'priority'    => $request->priority,
            'submit_date' => $request->submit_date,
            'status'      => $request->status,
        ]);
    }

    return redirect()->route('tasks.index')->with('success', 'Tasks created successfully.');
}



    public function edit(Task $task)
    {
        $user = auth()->user();

        // Team user can only edit their own tasks
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $users = $user->role === 'admin' ? \App\Models\User::all() : collect([$user]);

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'task' => 'nullable|string',
            'priority' => 'nullable|in:normal,urgent',
            'submit_date' => 'nullable|date',
            'status' => 'required|in:pending,working,completed',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

}
