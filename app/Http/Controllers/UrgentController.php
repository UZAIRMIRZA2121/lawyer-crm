<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\Notice;
use App\Models\Task;
use Illuminate\Http\Request;

class UrgentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Urgent Notices with filters
        $urgentNotices = Notice::with(['case', 'user', 'against_client'])
            ->where('priority', 'urgent')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('notice', 'like', "%$search%")
                        ->orWhereHas('case', fn($q) => $q->where('case_title', 'like', "%$search%"))
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
                });
            })
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->get();

        // Urgent Cases with filters
        $urgentCases = CaseModel::where('priority', 'urgent')
            ->when($search, function ($q) use ($search) {
                $q->where('case_title', 'like', "%$search%")
                    ->orWhere('case_number', 'like', "%$search%");
            })
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->get();

        // Urgent Tasks with filters
        $urgentTasks = Task::where('priority', 'urgent')
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->when($startDate, fn($q) => $q->whereDate('submit_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('submit_date', '<=', $endDate))
            ->latest()
            ->get();

        // Urgent Hearings with filters
        $urgentHearings = Hearing::where('priority', 'urgent')
            ->when($search, function ($q) use ($search) {
                $q->where('judge_name', 'like', "%$search%")
                    ->orWhere('nature', 'like', "%$search%")
                    ->orWhereHas('case', fn($q) => $q->where('case_title', 'like', "%$search%"));
            })
            ->when($startDate, fn($q) => $q->whereDate('next_hearing', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('next_hearing', '<=', $endDate))
            ->latest()
            ->get();

        return view('urgent', compact('urgentNotices', 'urgentCases', 'urgentTasks', 'urgentHearings'));
    }

}
