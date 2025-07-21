<?php

namespace App\Http\Controllers;
use App\Models\CaseModel;
use App\Models\Client;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = CaseModel::query()->with('client');

        // Apply client filtering if client_id provided
        if ($request->has('client_id')) {
            $client = Client::findOrFail($request->client_id);

            if ($user->role === 'team') {
                // Check assignment
                $isAssigned = $client->assignedUsers()
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$isAssigned) {
                    abort(403, 'You are not assigned to this client.');
                }
            }

            $query->where('client_id', $client->id);
        } else {
            // If no client filter and user is team, restrict to assigned clients' cases
            if ($user->role === 'team') {
                $query->whereHas('client.assignedUsers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            // Admins see all
        }

        // === Search filter ===
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

        // Calculate total transactions if admin
        $totalTransactionsAmount = null;
        if ($user->role === 'admin') {
            $totalTransactionsAmount = \App\Models\Transaction::sum('amount');
        }

        return view('cases.index', compact('cases', 'totalTransactionsAmount'));
    }




    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('cases.create', compact('clients'));
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

        // Enforce assignment check only if role is 'team'
        if ($user->role === 'team') {
            $client = $case->client; // assuming $case has a client() relationship

            $isAssigned = $client->assignedUsers()
                ->where('user_id', $user->id)
                ->exists();

            if (!$isAssigned) {
                abort(403, 'You are not assigned to this client.');
            }
            
            // Fetch only clients assigned to this team member
            $clients = Client::whereHas('assignedUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('name')->get();
        } else {
            // For other roles, get all clients
            $clients = Client::orderBy('name')->get();
        }

        return view('cases.edit', compact('case', 'clients'));
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
        ]);

        // Update fields
        $case->update([
            'case_number' => $request->case_number,
            'client_id' => $request->client_id,
            'case_title' => $request->case_title,
            'case_nature' => $request->case_nature,
            'description' => $request->description,
            'status' => $request->status,

            'judge_name' => $request->judge_name,
        ]);

        // Handle files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('case_files/' . $case->id, $fileName, 'public');

                // Save each file in case_files table
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
