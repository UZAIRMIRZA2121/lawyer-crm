<?php

namespace App\Http\Controllers;

use App\Models\CaseAgainstClient;
use App\Models\CaseModel;
use Illuminate\Http\Request;

class CaseAgainstClientController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseAgainstClient::with('case')->latest();

        // Filter by case_id if present
        if ($request->has('case_id') && $request->case_id != '') {
            $query->where('case_id', $request->case_id);
        }

        // Search by name, cnic, or phone if search term is present
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('cnic', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }

        $clients = $query->get();

        return view('case_against_clients.index', compact('clients'));
    }



    public function create()
    {
        $cases = CaseModel::all(); // dropdown for case selection
        return view('case_against_clients.create', compact('cases'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'case_id' => 'required|exists:case_models,id',
            'name' => 'required|string|max:255',
            'cnic' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:25',
        ]);
        CaseAgainstClient::create($request->all());

        return redirect()->route('case-against-clients.index')->with('success', 'Against client added successfully.');
    }

    public function show(CaseAgainstClient $caseAgainstClient)
    {
        return view('case_against_clients.show', compact('caseAgainstClient'));
    }

    public function edit(CaseAgainstClient $caseAgainstClient)
    {
        $cases = CaseModel::all();
        return view('case_against_clients.create', compact('caseAgainstClient', 'cases'));
    }

    public function update(Request $request, CaseAgainstClient $caseAgainstClient)
    {
        $request->validate([
            'case_id' => 'required|exists:case_models,id',
            'name' => 'required|string|max:255',
            'cnic' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:25',
        ]);

        $caseAgainstClient->update($request->all());

        return redirect()->route('case-against-clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(CaseAgainstClient $caseAgainstClient)
    {
        $caseAgainstClient->delete();
        return redirect()->route('case-against-clients.index')->with('success', 'Client deleted successfully.');
    }
}
