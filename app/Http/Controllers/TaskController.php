<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskUpload;
use Illuminate\Http\Request;
use App\Models\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Task::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }



        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('task', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date filters: filter tasks by created_at between start_date and end_date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }


        // Existing priority filter
        if ($request->filled('priority')) {
            $allowedPriorities = ['normal', 'urgent', 'important'];
            if (in_array($request->priority, $allowedPriorities)) {
                $query->where('priority', $request->priority);
            }
        }

        // Existing status filter
        if ($request->filled('status')) {
            $allowedStatuses = ['pending', 'done'];
            if (in_array($request->status, $allowedStatuses)) {
                $query->where('status', $request->status);
            }
        }

        // Existing sub_status filter
        if ($request->filled('sub_status')) {
            $query->where('sub_status', $request->sub_status);
        }

        // Order by group_id and then by created_at descending
        $tasks = $query->orderBy('group_id')->orderBy('created_at', 'desc')->get();

        // Group tasks by group_id in PHP
        $groupedTasks = $tasks->groupBy('group_id');

        return view('tasks.index', ['tasks' => $groupedTasks]);
    }



    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'team')->get();
        } else {
            $users = collect([$user]);  // only allow self-creation for team
        }

        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'task' => 'required|string',
            'priority' => 'required|in:normal,urgent,important',
            'submit_date' => 'required|date',
            'status' => 'required|in:pending,done',
            'sub_status' => 'required|in:drafting,research,note',
            'upload_files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        try {
            $createdCount = 0;

            $maxGroupId = Task::max('group_id') ?? 0;

            // Use user_ids from request or default to authenticated user ID in an array
            $userIds = $request->input('user_ids', [$authUser->id]);

            foreach ($userIds as $userId) {
                // Only admin or the user themselves can create the task
                if ($authUser->role !== 'admin' && $authUser->id != $userId) {
                    continue;
                }

                $task = Task::create([
                    'user_id' => $userId,
                    'group_id' => $maxGroupId + 1,
                    'title' => $request->title,   // Add this line
                    'task' => $request->task,
                    'priority' => $request->priority,
                    'submit_date' => $request->submit_date,
                    'status' => $request->status,
                    'sub_status' => $request->sub_status,
                ]);
                if ($request->hasFile('upload_files')) {
                    foreach ($request->file('upload_files') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $filenameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

                        $fileName = $filenameWithoutExt . '-' . time() . '.' . $extension;

                        // Store all files directly in 'task_uploads' folder on public disk
                        $filePath = $file->storeAs(
                            'task_uploads',  // no task ID folder
                            $fileName,
                            'public'
                        );

                        TaskUpload::create([
                            'task_id' => $task->id,
                            'user_id' => $userId,
                            'upload_files' => $filePath,  // e.g. "task_uploads/app-1754746381.png"
                        ]);
                    }
                }




                $createdCount++;
            }

            if ($createdCount === 0) {
                return back()->withErrors('No tasks created. You may only assign tasks to yourself.');
            }
            dd(234);
            return redirect()->route('tasks.index')->with('success', 'Tasks created successfully.');
        } catch (\Exception $e) {
            \Log::error('Task creation failed: ' . $e->getMessage());
            return back()->withErrors('An error occurred while creating the tasks. Please try again.');
        }
    }




    public function edit(Task $task)
    {
        $user = auth()->user();

        // Team user can only edit their own tasks
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        // Load related uploads
        $uploads = TaskUpload::where('task_id', $task->id)->get();
        $users = $user->role === 'admin' ? \App\Models\User::all() : collect([$user]);

        return view('tasks.edit', compact('task', 'users', 'uploads'));
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        // Only allow admin or the task owner
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        // Validate incoming data
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'task' => 'nullable|string',
            'priority' => 'nullable|in:normal,urgent,important',
            'submit_date' => 'nullable|date',
            'status' => 'required|in:pending,done',
            'sub_status' => 'nullable|in:drafting,research,note',
        ]);

        // Only update allowed fields explicitly
        $task->update([
            'user_id' => $request->user_id ?? $task->user_id,
            'task' => $request->task ?? $task->task,
            'priority' => $request->priority ?? $task->priority,
            'submit_date' => $request->submit_date ?? $task->submit_date,
            'status' => $request->status,
            'sub_status' => $request->sub_status ?? $task->sub_status,
        ]);

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

    public function taskuploadDestroy(TaskUpload $upload)
    {
        // Delete file from storage
        Storage::disk('public')->delete($upload->upload_files);

        // Delete db record
        $upload->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    public function destroyGroup($groupId)
    {
        $user = auth()->user();

        // If you want to restrict deletion to admins only
        if ($user->role !== 'admin') {
            return back()->withErrors('Unauthorized action.');
        }

        // Delete all tasks with the given group_id
        Task::where('group_id', $groupId)->delete();

        return redirect()->route('tasks.index')->with('success', 'Task group deleted successfully.');
    }



}
