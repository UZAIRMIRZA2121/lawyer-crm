<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Task;
use Illuminate\Http\Request;

class DraftController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $status = $request->input('status'); // ✅ New status filter

    // Draft Cases
    $draftCases = CaseModel::where('sub_status', 'draft')
        ->when($search, function ($q) use ($search) {
            $q->where('case_title', 'like', "%$search%")
                ->orWhere('case_number', 'like', "%$search%");
        })
        ->when($status, fn($q) => $q->where('status', $status)) // ✅ Filter by status
        ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
        ->latest()
        ->get();

    // Draft Tasks
    $draftTasks = Task::where('sub_status', 'drafting')
        ->when($search, function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
        })
        ->when($status, fn($q) => $q->where('status', $status)) // ✅ Filter by status
        ->when($startDate, fn($q) => $q->whereDate('submit_date', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('submit_date', '<=', $endDate))
        ->latest()
        ->get();

    return view('draft', compact('draftCases', 'draftTasks'));
}


}
