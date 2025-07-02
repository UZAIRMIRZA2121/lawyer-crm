<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display all clients
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    // Show form to create a new client
    public function create()
    {
        return view('clients.create');
    }

    // Store a new client
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'cnic_front' => 'nullable|image|max:2048',
            'cnic_back' => 'nullable|image|max:2048',
        ]);

        // Create the client first (without images)
        $client = Client::create([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        // Create folder name: {id}-{slugified_name}
        $folder = 'clients/' . $client->id . '-' . \Str::slug($client->name);

        $updateData = [];

        // Store CNIC front image if present
        if ($request->hasFile('cnic_front')) {
            $updateData['cnic_front'] = $request->file('cnic_front')->store($folder, 'public');
        }

        // Store CNIC back image if present
        if ($request->hasFile('cnic_back')) {
            $updateData['cnic_back'] = $request->file('cnic_back')->store($folder, 'public');
        }

        // Update the client record with image paths
        if (!empty($updateData)) {
            $client->update($updateData);
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
        return view('clients.edit', compact('client'));
    }

    // Update a client
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'cnic_front' => 'nullable|image|max:2048',
            'cnic_back' => 'nullable|image|max:2048',
        ]);

        // Update all text fields first
        $client->update([
            'name' => $request->name,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        // Determine folder based on updated name
        $folder = 'clients/' . $client->id . '-' . \Str::slug($client->name);

        $updateData = [];

        // Handle CNIC front image
        if ($request->hasFile('cnic_front')) {
            // Optionally delete old image
            if ($client->cnic_front && \Storage::disk('public')->exists($client->cnic_front)) {
                \Storage::disk('public')->delete($client->cnic_front);
            }

            // Store new file
            $updateData['cnic_front'] = $request->file('cnic_front')->store($folder, 'public');
        }

        // Handle CNIC back image
        if ($request->hasFile('cnic_back')) {
            // Optionally delete old image
            if ($client->cnic_back && \Storage::disk('public')->exists($client->cnic_back)) {
                \Storage::disk('public')->delete($client->cnic_back);
            }

            // Store new file
            $updateData['cnic_back'] = $request->file('cnic_back')->store($folder, 'public');
        }

        // Update image paths if needed
        if (!empty($updateData)) {
            $client->update($updateData);
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
