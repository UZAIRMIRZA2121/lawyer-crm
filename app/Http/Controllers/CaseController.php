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
    $query = CaseModel::query();

    if ($request->has('client_id')) {
        $client = Client::findOrFail($request->client_id);

        if ($user->role === 'team') {
            // Team members must be assigned to the client
            $isAssigned = $client->assignedUsers()
                ->where('user_id', $user->id)
                ->exists();

            if (!$isAssigned) {
                abort(403, 'You are not assigned to this client.');
            }
        }
     
        // For both admin and team, filter by client_id
        $query->where('client_id', $client->id);

    } else {
        if ($user->role === 'team') {
            // Team members without client_id see only assigned clients' cases
            $query->whereHas('client.assignedUsers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        // Admins and other roles see all cases when no client_id
    }

    $cases = $query->latest()->paginate(15);
    // If admin, calculate sum of all transactions
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
            'description' => 'nullable|string',
            'status' => 'required|string',
            'hearing_date' => 'nullable|date|after:now',
            'judge_name' => 'nullable|string',
        ]);

        // If you are using datetime-local input, convert it to Carbon for safety
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
        return view('cases.show', compact('case'));
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
            'hearing_date' => 'nullable|date',
            'judge_name' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240', // 10 MB per file
        ]);

        // Update fields
        $case->update([
            'case_number' => $request->case_number,
            'client_id' => $request->client_id,
            'case_title' => $request->case_title,
            'description' => $request->description,
            'status' => $request->status,
            'hearing_date' => $request->hearing_date,
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
