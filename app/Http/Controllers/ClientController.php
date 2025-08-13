<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display all clients
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'team') {
            // Start from only assigned clients
            $query = $user->assignedClients()->latest();
        } else {
            // Admin can see all clients
            $query = Client::with('assignedUsers')->latest();
        }

        // Apply search if present
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cnic', 'like', '%' . $searchTerm . '%')
                    ->orWhere('contact_no', 'like', '%' . $searchTerm . '%');
            });
        }

        $clients = $query->paginate(10);

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
            'referral_by' => 'nullable|string|max:255',
            'upload_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
            'description' => 'nullable|string',
        ]);

        try {
            // Step 1: Create client without image/upload_files paths
            $client = Client::create([
                'name' => $request->name,
                'cnic' => $request->cnic,
                'contact_no' => $request->contact_no,
                'email' => $request->email,
                'address' => $request->address,
                'referral_by' => $request->referral_by,
                'description' => $request->description,
            ]);

            // Step 2: Create folder for storing upload_files
            $folder = 'clients/' . $client->id . '-' . \Str::slug($client->name);
            $updateData = [];

            // Step 3: Handle CNIC front upload
            if ($request->hasFile('cnic_front')) {
                $file = $request->file('cnic_front');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    . '_' . rand(100000, 999999)
                    . '.' . $file->getClientOriginalExtension();
                $updateData['cnic_front'] = $file->storeAs($folder, $fileName, 'public');
            }

            // Step 4: Handle CNIC back upload
            if ($request->hasFile('cnic_back')) {
                $file = $request->file('cnic_back');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    . '_' . rand(100000, 999999)
                    . '.' . $file->getClientOriginalExtension();
                $updateData['cnic_back'] = $file->storeAs($folder, $fileName, 'public');
            }
            $upload_filesArray = [];
            // Step 5: Handle multiple upload_files upload (store with random digits)
            $upload_filesArray = $client->upload_files ?? []; // Keep existing upload_files if editing
            if ($request->hasFile('upload_files')) {
                foreach ($request->file('upload_files') as $file) {
                    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                        . '_' . rand(100000, 999999)
                        . '.' . $file->getClientOriginalExtension();

                    $upload_filesArray[] = $file->storeAs($folder, $fileName, 'public');
                }
            }
            $updateData['files'] = $upload_filesArray;
            // Step 6: Update client with image/upload_files paths if any
            if (!empty($updateData)) {
                $client->update($updateData);
            }

            // Step 7: Assign multiple users to this client
            if ($request->filled('assigned_to')) {
                $client->assignedUsers()->sync($request->assigned_to);
            }
         
            return redirect()->route('clients.index')->with('success', 'Client created successfully.');
        } catch (\Exception $e) {
            \Log::error('Client Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to save client. Error: ' . $e->getMessage());
        }
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
        foreach ($client->cases as $case) {
            // Delete related notices
            $case->notices()->delete();

            // Delete related against clients
            $case->againstClients()->delete();

            // Delete related hearings
            $case->hearings()->delete();

            // Delete the case itself
            $case->delete();
        }

        // Finally, delete the client
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client and all related cases and data deleted successfully.');
    }

}
