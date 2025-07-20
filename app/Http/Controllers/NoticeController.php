<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\CaseModel;
use App\Models\User;
use App\Models\CaseAgainstClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with(['case', 'user'])->latest()->get();
        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        $cases = CaseModel::all();
        $users = User::all();
        $clients = CaseAgainstClient::all();

        return view('notices.create', compact('cases', 'users', 'clients'));
    }

    public function store(Request $request)
    {
        // Validate all fields
        $request->validate([
            'case_id' => 'nullable|exists:case_models,id',
            'user_id' => 'nullable|exists:users,id',
            'against_client_id' => 'nullable|exists:case_against_clients,id',
            'notice' => 'required|string',
            'status' => 'required|boolean',

            'judge_name' => 'nullable|string|max:255',
            'case_number' => 'nullable|string|max:255',
            'plaintiff_name' => 'nullable|string|max:255',
            'plaintiff_address' => 'nullable|string|max:255',
            'defendant_name' => 'nullable|string|max:255',
            'defendant_father_address' => 'nullable|string|max:255',
            'defendant_role' => 'nullable|string|max:255',
            'hearing_date' => 'nullable|date',
            'hearing_time' => 'nullable',
            'month_year' => 'nullable|string|max:255',
        ]);

        // Set authenticated user id
        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Render summon print view HTML
        $html = view('notices.print-summon', ['data' => $data])->render();

        // Encode HTML as base64
        $base64Html = base64_encode($html);

        // Add base64 HTML to data
        $data['notice_base64'] = $base64Html;

        // Save notice including base64 encoded HTML
        Notice::create($data);

        // Return summon print view normally (for printing)
        return response($html);
    }


    public function edit(Notice $notice)
    {
        $cases = CaseModel::all();
        $users = User::all();
        $clients = CaseAgainstClient::all();

        return view('notices.edit', compact('notice', 'cases', 'users', 'clients'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'case_id' => 'nullable|exists:case_models,id',
            'against_client_id' => 'nullable|exists:case_against_clients,id',
            'notice' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // ✅ Set current authenticated user

        $notice->update($data);

        return redirect()
            ->route('notices.index')
            ->with('success', 'Notice updated successfully.');
    }


    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
    }

    public function getClientsByCase($caseId)
    {
        // Fetch the case with its main client
        $case = CaseModel::with('client')->find($caseId);

        if (!$case) {
            return response()->json(['error' => 'Case not found'], 404);
        }

        // Get against clients separately
        $againstClients = CaseAgainstClient::where('case_id', $caseId)
            ->select('id', 'name', 'address', 'cnic', 'phone') // add what you need
            ->get();

        // Log full case and against clients (for debug)
        Log::info('Case Data:', $case->toArray());
        Log::info('Against Clients:', $againstClients->toArray());

        // Return both datasets
        return response()->json([
            'case' => $case,
            'against_clients' => $againstClients
        ]);
    }
    public function print(Request $request)
    {
        $data = $request->all();
        dd($data);

        // Render the Blade view as HTML string
        $html = view('notices.print-summon', compact('data'))->render();


        dd($html);
        return view('notices.print-summon', compact('data'));
    }
}
