<?php

namespace App\Http\Controllers;
use App\Models\CaseModel;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = CaseModel::with('client');

        // === TEAM ROLE: Restrict to assigned case IDs from client_user table ===
        // if ($user->role === 'team') {
        //     $assignedCaseIds = \DB::table('client_user')
        //         ->where('user_id', $user->id)
        //         ->pluck('case_id')
        //         ->toArray();

        //     $query->whereIn('id', $assignedCaseIds);
        // }

        // === ADMIN ROLE: Optional filtering by client_id
        if ($user->role === 'admin' && $request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // === Date Range Filter ===
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // === Search Filter ===
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                    ->orWhere('case_title', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }
        // === Priority Filter ===
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // === Status Filter ===
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // ✅ New Sub Status filter
        if ($sub_status = request('sub_status')) {
            $query->where('sub_status', $sub_status);
        }


        $cases = $query->latest()->get();

        // === Admin-only: total transactions
        $totalTransactionsAmount = null;
        if ($user->role === 'admin') {
            $totalTransactionsAmount = \App\Models\Transaction::sum('amount');
        }

        return view('cases.index', compact('cases', 'totalTransactionsAmount'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $users = User::where('role', 'team')->get();
        return view('cases.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'case_number' => 'nullable|unique:case_models,case_number',
            'client_id' => 'nullable|exists:clients,id',
            'case_title' => 'nullable|string',
            'case_nature' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'sub_status' => 'nullable|in:draft,pursue', // ✅ added validation
            'priority' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'commission_amount' => 'nullable|numeric',
            'judge_name' => 'nullable|string',
            'hearing_date' => 'nullable|date',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
            'created_at' => 'nullable|date', // <-- validation for created_at
        ]);

        $data = $request->all();

        if ($request->filled('hearing_date')) {
            $data['hearing_date'] = \Carbon\Carbon::parse($request->hearing_date);
        }

        if ($request->filled('created_at')) {
            $data['created_at'] = \Carbon\Carbon::parse($request->created_at);
        }

        // Create the case with manual created_at if provided
        $case = CaseModel::create($data);

        // Assign users to the client through pivot with case_id
        $client = $case->client;

        if ($client && $request->has('assigned_to')) {
            $assignedUserIds = $request->input('assigned_to', []);

            $syncData = [];
            foreach ($assignedUserIds as $userId) {
                $syncData[$userId] = ['case_id' => $case->id];
            }

            $client->assignedUsers()->sync($syncData);
        }

        return redirect()->route('cases.index')->with('success', 'Case created successfully.');
    }




    public function show(CaseModel $case)
    {
        $case->load('client');
        $all_case_files = $case->files()->latest()->get();
        return view('cases.show', compact('case', 'all_case_files'));
    }

    public function edit(CaseModel $case)
    {
        $user = auth()->user();

        // if ($user->role === 'team') {
        //     $client = $case->client; // make sure CaseModel has client() relationship

        //     // Check if the current user is assigned to this client
        //     $isAssigned = $client->assignedUsers()
        //         ->where('user_id', $user->id)
        //         ->exists();

        //     if (!$isAssigned) {
        //         abort(403, 'You are not assigned to this client.');
        //     }

        //     // Get only clients assigned to this user
        //     $clients = Client::whereHas('assignedUsers', function ($query) use ($user) {
        //         $query->where('user_id', $user->id);
        //     })->orderBy('name')->get();
        // } else {
            // Admin or others get all clients
            $clients = Client::orderBy('name')->get();
        // }

        // Get all team users for assigned_to select
        $users = User::where('role', 'team')->get();
        // Get assigned user IDs for this case
        $assignedUserIds = DB::table('client_user')
            ->where('case_id', $case->id)
            ->pluck('user_id')
            ->toArray();

        return view('cases.edit', compact('case', 'clients', 'users', 'assignedUserIds'));
    }




    public function update(Request $request, CaseModel $case)
    {
        $request->validate([
            'case_number' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'case_title' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,pending,closed,done', // add any other statuses you use
            'case_nature' => 'nullable|string',
            'judge_name' => 'nullable|string',
            'priority' => 'nullable|in:urgent,important,normal',
            'commission_amount' => 'nullable|numeric|min:0',
            'files.*' => 'nullable|file|max:10240', // 10 MB per file
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
            'amount' => 'nullable|numeric',
            'sub_status' => 'nullable|in:draft,pursue', // ✅ added validation

        ]);

        // Update case fields
        $case->update([
            'case_number' => $request->case_number,
            'client_id' => $request->client_id,
            'case_title' => $request->case_title,
            'case_nature' => $request->case_nature,
            'description' => $request->description,
            'status' => $request->status,
            'judge_name' => $request->judge_name,
            'priority' => $request->priority,
            'commission_amount' => $request->commission_amount,
            'amount' => $request->amount,
            'sub_status' => $request->sub_status,
        ]);

        // Sync assigned users for the client related to this case
        $client = $case->client;

        if ($client) {
            $assignedUserIds = $request->input('assigned_to', []);

            $syncData = [];
            foreach ($assignedUserIds as $userId) {
                $syncData[$userId] = ['case_id' => $case->id];
            }

            $client->assignedUsers()->sync($syncData);
        }

        // Handle files upload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('case_files/' . $case->id, $fileName, 'public');

                $lastSequence = $case->files()->max('sequence') ?? 0;
                $nextSequence = $lastSequence + 1;

                \App\Models\CaseFile::create([
                    'case_id' => $case->id,
                    'user_id' => auth()->id(),
                    'file_path' => $filePath,
                    'sequence' => $nextSequence,
                ]);
            }
        }

        return redirect()->route('cases.index')->with('success', 'Case updated successfully.');
    }


    public function destroy(CaseModel $case)
    {
        // Delete related notices
        $case->notices()->delete();

        // Delete related against clients
        $case->againstClients()->delete();

        // Delete related hearings
        $case->hearings()->delete();

        // Now delete the case itself
        $case->delete();

        return redirect()->route('cases.index')->with('success', 'Case and all related records deleted successfully.');
    }

    public function printReport($id)
    {
        $case = CaseModel::with(['client', 'againstClients', 'hearings'])->findOrFail($id);

        return view('cases.print_report', compact('case'));
    }
    public function updateAmounts(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
        ]);

        $case->update($validated);

        return redirect()->back()->with('success', 'Amounts updated successfully.');
    }

}
