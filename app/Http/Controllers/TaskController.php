<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\HasFactory;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Start query with eager loading user relation
        $query = Task::with('user')->latest();

        // If user is not admin, restrict tasks to their own only
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Apply priority filter if present and valid
        if ($request->filled('priority')) {
            $allowedPriorities = ['normal', 'urgent', 'important'];
            if (in_array($request->priority, $allowedPriorities)) {
                $query->where('priority', $request->priority);
            }
        }

        // Apply status filter if present and valid
        if ($request->filled('status')) {
            $allowedStatuses = ['pending', 'done'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        // Get filtered tasks
        $tasks = $query->get();

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
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'task' => 'required|string',
            'priority' => 'required|in:normal,urgent,important',
            'submit_date' => 'required|date',
            'status' => 'required|in:pending,done',
        ]);

        $createdCount = 0;

        foreach ($request->user_ids as $userId) {
            if ($authUser->role !== 'admin' && $authUser->id != $userId) {
                continue;
            }

            Task::create([
                'user_id' => $userId,
                'task' => $request->task,
                'priority' => $request->priority,
                'submit_date' => $request->submit_date,
                'status' => $request->status,
            ]);
            $createdCount++;
        }

        if ($createdCount === 0) {
            return back()->withErrors('No tasks created. You may only assign tasks to yourself.');
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
