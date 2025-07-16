<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\CaseModel;
use App\Models\User;
use App\Models\CaseAgainstClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'case_id' => 'nullable|exists:case_models,id',
            'user_id' => 'nullable|exists:users,id',
            'against_client_id' => 'nullable|exists:case_against_clients,id',
            'notice' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // ✅ Set current authenticated user

        Notice::create($data);

        return redirect()->route('notices.index')->with('success', 'Notice created successfully.');
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
        $clients = CaseAgainstClient::where('case_id', $caseId)
            ->select('id', 'name')
            ->get();

        return response()->json($clients);
    }
}
