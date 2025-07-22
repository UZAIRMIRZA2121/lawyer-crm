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
        if ($user->role === 'team') {
            $assignedCaseIds = \DB::table('client_user')
                ->where('user_id', $user->id)
                ->pluck('case_id')
                ->toArray();

            // Apply filter: show only those cases that match assigned case IDs
            $query->whereIn('id', $assignedCaseIds);
        }

        // === ADMIN ROLE: Optional filtering by client_id
        if ($user->role === 'admin' && $request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
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

        $cases = $query->latest()->paginate(15);

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
            'case_number' => 'required|unique:case_models,case_number',
            'client_id' => 'required|exists:clients,id',
            'case_title' => 'required|string',
            'case_nature' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'amount' => 'nullable|numeric',
            'judge_name' => 'nullable|string',
            'hearing_date' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->filled('hearing_date')) {
            $data['hearing_date'] = \Carbon\Carbon::parse($request->hearing_date);
        }

        CaseModel::create($data);

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

        if ($user->role === 'team') {
            $client = $case->client; // make sure CaseModel has client() relationship

            // Check if the current user is assigned to this client
            $isAssigned = $client->assignedUsers()
                ->where('user_id', $user->id)
                ->exists();

            if (!$isAssigned) {
                abort(403, 'You are not assigned to this client.');
            }

            // Get only clients assigned to this user
            $clients = Client::whereHas('assignedUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('name')->get();
        } else {
            // Admin or others get all clients
            $clients = Client::orderBy('name')->get();
        }

        // Get all team users for assigned_to select
        $users = User::where('role', 'team')->get();
        // Get assigned user IDs for this case
        $assignedUserIds = DB::table('client_user')
            ->where('case_id', $case->id)
            ->pluck('user_id')
            ->toArray();

        return view('cases.edit', compact('case', 'clients', 'users','assignedUserIds'));
    }




    public function update(Request $request, CaseModel $case)
    {
        $request->validate([
            'case_number' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'case_title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:open,pending,closed',
            'case_nature' => 'nullable|string',
            'judge_name' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240', // 10 MB per file
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
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
        ]);

        // Sync assigned users for the client related to this case
        // Assuming assigned users are stored on the Client model pivot table (client_user)
        $client = $case->client;

        if ($client) {
            $assignedUserIds = $request->input('assigned_to', []);

            // Prepare sync data with pivot case_id
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
        $case->delete();
        return redirect()->route('cases.index')->with('success', 'Case deleted successfully.');
    }
}
