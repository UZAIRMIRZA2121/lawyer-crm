<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display all clients
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'team') {
            // Only clients assigned to this user
            $clients = $user->assignedClients()->latest()->paginate(10);
        } else {
            // Admin can see all
            $clients = Client::with('assignedUsers')->latest()->paginate(10);
        }

        return view('clients.index', compact('clients'));
    }


    // Show form to create a new client
    public function create()
    {
        $users = \App\Models\User::where('role', 'team')->get(); // Get all team members
        return view('clients.create', compact('users'));
    }

    // Store a new client
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'cnic_front' => 'nullable|image|max:2048',
            'cnic_back' => 'nullable|image|max:2048',
            'assigned_to' => 'array|nullable',
            'assigned_to.*' => 'exists:users,id',
        ]);

        // Step 1: Create client without image paths
        $client = Client::create([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        // Step 2: Create folder for storing CNICs
        $folder = 'clients/' . $client->id . '-' . \Str::slug($client->name);
        $updateData = [];

        // Step 3: Handle CNIC front upload
        if ($request->hasFile('cnic_front')) {
            $updateData['cnic_front'] = $request->file('cnic_front')->store($folder, 'public');
        }

        // Step 4: Handle CNIC back upload
        if ($request->hasFile('cnic_back')) {
            $updateData['cnic_back'] = $request->file('cnic_back')->store($folder, 'public');
        }

        // Step 5: Update client with image paths if any
        if (!empty($updateData)) {
            $client->update($updateData);
        }

        // Step 6: Assign multiple users to this client
        if ($request->filled('assigned_to')) {
            $client->assignedUsers()->sync($request->assigned_to);
        }

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }




    // Show a single client
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    // Show form to edit a client
    public function edit(Client $client)
    {
        $users = \App\Models\User::where('role', 'team')->get(); // Get all team members
        return view('clients.edit', compact('client', 'users'));
    }

    // Update a client
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'cnic_front' => 'nullable|image|max:2048',
            'cnic_back' => 'nullable|image|max:2048',
            'assigned_to' => 'array|nullable',
            'assigned_to.*' => 'exists:users,id',
        ]);

        // Step 1: Update text fields
        $client->update([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        // Step 2: Define folder path
        $folder = 'clients/' . $client->id . '-' . \Str::slug($client->name);
        $updateData = [];

        // Step 3: Update CNIC front image
        if ($request->hasFile('cnic_front')) {
            if ($client->cnic_front && \Storage::disk('public')->exists($client->cnic_front)) {
                \Storage::disk('public')->delete($client->cnic_front);
            }
            $updateData['cnic_front'] = $request->file('cnic_front')->store($folder, 'public');
        }

        // Step 4: Update CNIC back image
        if ($request->hasFile('cnic_back')) {
            if ($client->cnic_back && \Storage::disk('public')->exists($client->cnic_back)) {
                \Storage::disk('public')->delete($client->cnic_back);
            }
            $updateData['cnic_back'] = $request->file('cnic_back')->store($folder, 'public');
        }

        // Step 5: Update image paths
        if (!empty($updateData)) {
            $client->update($updateData);
        }

        // Step 6: Sync assigned users (Many-to-Many)
        if ($request->filled('assigned_to')) {
            $client->assignedUsers()->sync($request->assigned_to);
        } else {
            // If no user selected, detach all
            $client->assignedUsers()->detach();
        }

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }


    // Delete a client
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
