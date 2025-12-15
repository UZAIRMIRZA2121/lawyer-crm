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
    $caseId = $request->query('case_id');
    $priority = $request->query('priority');
    $status = $request->query('status');
    $search = $request->query('search');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $query = Hearing::query();

    if ($caseId) {
        $case = CaseModel::find($caseId);
        if ($case) {
            $query = $case->hearings();   // case-specific hearings
        }
    } else {
        $case = null;
    }

    // === Priority filter
    if ($priority) {
        $query->where('priority', $priority);
    }

    // === Status filter
    if ($status) {
        $query->where('status', $status);
    }

    // === Search filter (case + hearing fields)
    if ($search) {
        $query->where(function ($q) use ($search) {
            // Search hearing fields
            $q->where('judge_remarks', 'like', "%{$search}%")
             

            // Search case fields
              ->orWhereHas('case', function ($q2) use ($search) {
                  $q2->where('case_title', 'like', "%{$search}%")
                     ->orWhere('case_number', 'like', "%{$search}%");
              });
        });
    }

    // === Date filters
    if ($startDate) {
        $query->whereDate('next_hearing', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('next_hearing', '<=', $endDate);
    }

    $hearings = $query->latest()->get();

    return view('hearings.index', compact('case', 'hearings'));
}



    // Show form to create hearing
    public function create(Request $request, CaseModel $case)
    {
        $caseId = $request->query('case_id');
        $case = CaseModel::find($caseId);

        return view('hearings.create', compact('case', ));
    }

    // Store new hearing
    public function store(Request $request)
    {

        $validated = $request->validate([
            'judge_name' => 'nullable|string|max:255',
            'judge_remarks' => 'nullable|string',
            'my_remarks' => 'nullable|string',
            'next_hearing' => 'nullable|date',
            'priority' => 'nullable|in:important,normal,urgent',
            'case_id' => 'nullable|exists:case_models,id',  // Validate case_id from query/form
            'nature' => 'nullable|string|max:255', // <- new line
            'status' => 'nullable|in:pending,done', // ✅ New line
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
            'judge_name' => 'nullable|string|max:255',
            'judge_remarks' => 'nullable|string',
            'my_remarks' => 'nullable|string',
            'next_hearing' => 'nullable|date',
            'priority' => 'nullable|in:important,normal,urgent',
            'nature' => 'nullable|string|max:255', // <- new line
            'status' => 'nullable|in:pending,done', // ✅ New line
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
