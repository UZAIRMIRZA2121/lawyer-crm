<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\CaseModel; // adjust to your Case model name
use App\Models\Hearing;
use App\Models\User; // assuming your team members are users

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'team') {
            return redirect()->route('tasks.index');
        }
        $clientsCount = Client::count();
        $casesCount = CaseModel::count();
        $hearingsCount = Hearing::count();
        $teamMembersCount = User::where('role', 'team')->count();

        $todayHearings = Hearing::whereDate('next_hearing', today())->with('case')->get();
        $tomorrowHearings = Hearing::whereDate('next_hearing', today()->addDay())->with('case')->get();

        return view('dashboard', compact(
            'clientsCount',
            'casesCount',
            'hearingsCount',
            'teamMembersCount',
            'todayHearings',
            'tomorrowHearings'
        ));
    }

}
