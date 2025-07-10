<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\CaseModel;
use Illuminate\Http\Request;

class HearingController extends Controller
{
    // Show list of hearings for a case
    public function index(CaseModel $case)
    {
        $hearings = $case->hearings()->latest()->paginate(10);

        return view('hearings.index', compact('case', 'hearings'));
    }

    // Show form to create hearing
    public function create(CaseModel $case)
    {
        return view('hearings.create', compact('case'));
    }

    // Store new hearing
    public function store(Request $request, CaseModel $case)
    {
      
        $request->validate([
            'judge_name' => 'required|string|max:255',
            'judge_remarks' => 'nullable|string',
            'my_remarks' => 'nullable|string',
            'next_hearing' => 'nullable|date',
            'priority' => 'required|in:important,normal',
        ]);
        $case->hearings()->create($request->all());

        return redirect()->route('hearings.index', $case)->with('success', 'Hearing created successfully.');
    }

    // Show form to edit hearing
    public function edit(CaseModel $case, Hearing $hearing)
    {
        return view('hearings.edit', compact('case', 'hearing'));
    }

    // Update hearing
    public function update(Request $request, CaseModel $case, Hearing $hearing)
    {
        $request->validate([
            'judge_name' => 'required|string|max:255',
            'judge_remarks' => 'nullable|string',
            'my_remarks' => 'nullable|string',
            'next_hearing' => 'nullable|date',
            'priority' => 'required|in:important,normal',
        ]);

        $hearing->update($request->all());

        return redirect()->route('hearings.index', $case)->with('success', 'Hearing updated successfully.');
    }

    // Delete hearing
    public function destroy(CaseModel $case, Hearing $hearing)
    {
        $hearing->delete();
        return redirect()->route('hearings.index', $case)->with('success', 'Hearing deleted successfully.');
    }
}
