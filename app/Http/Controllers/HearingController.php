<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\CaseModel;
use Illuminate\Http\Request;

class HearingController extends Controller
{
    // Show list of hearings for a case
    public function index(Request $request)
    {
        $case = CaseModel::find($request->query('case_id'));

        $hearings = $case->hearings()->latest()->paginate(10);

        return view('hearings.index', compact('case', 'hearings'));
    }

    // Show form to create hearing
    public function create(CaseModel $case)
    {
        return view('hearings.create', compact('case'));
    }

    // Store new hearing
    public function store(Request $request)
    {

        $validated = $request->validate([
            'judge_name' => 'required|string|max:255',
            'judge_remarks' => 'nullable|string',
            'my_remarks' => 'nullable|string',
            'next_hearing' => 'nullable|date',
            'priority' => 'required|in:important,normal',
            'case_id' => 'required|exists:case_models,id',  // Validate case_id from query/form
                'nature' => 'nullable|string|max:255', // <- new line
        ]);

        // No route-model binding, so fetch CaseModel manually if needed
        $case = CaseModel::findOrFail($validated['case_id']);

        Hearing::create($validated);

        return redirect()->route('hearings.index', ['case_id' => $case->id])->with('success', 'Hearing created successfully.');
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
                'nature' => 'nullable|string|max:255', // <- new line
        ]);

        $hearing->update($request->all());

        return redirect()->route('hearings.index', ['case_id' => $request->case_id])->with('success', 'Hearing updated successfully.');
    }

    // Delete hearing
public function destroy(Request $request, Hearing $hearing)
{
    $caseId = $request->input('case_id') ?? $request->query('case_id');

    if (!$caseId || $hearing->case_id != $caseId) {
        abort(404);
    }

    $hearing->delete();

    return redirect()->route('hearings.index', ['case_id' => $caseId])
                     ->with('success', 'Hearing deleted successfully.');
}

}
