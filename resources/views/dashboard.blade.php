@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <!-- Clients Box -->
        <div class="col-md-3">
            <div class="card border-primary mb-3">
                <div class="card-body text-primary text-center">
                    <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    <h3 class="card-title mt-2">{{ $clientsCount }}</h3>
                    <p class="card-text">Total Clients</p>
                </div>
            </div>
        </div>

        <!-- Cases Box -->
        <div class="col-md-3">
            <div class="card border-success mb-3">
                <div class="card-body text-success text-center">
                    <i class="bi bi-briefcase-fill" style="font-size: 2rem;"></i>
                    <h3 class="card-title mt-2">{{ $casesCount }}</h3>
                    <p class="card-text">Total Cases</p>
                </div>
            </div>
        </div>

        <!-- Hearings Box -->
        <div class="col-md-3">
            <div class="card border-warning mb-3">
                <div class="card-body text-warning text-center">
                    <i class="bi bi-calendar-event-fill" style="font-size: 2rem;"></i>
                    <h3 class="card-title mt-2">{{ $hearingsCount }}</h3>
                    <p class="card-text">Total Hearings</p>
                </div>
            </div>
        </div>

        <!-- Team Members Box -->
        <div class="col-md-3">
            <div class="card border-danger mb-3">
                <div class="card-body text-danger text-center">
                    <i class="bi bi-person-badge-fill" style="font-size: 2rem;"></i>
                    <h3 class="card-title mt-2">{{ $teamMembersCount }}</h3>
                    <p class="card-text">Team Members</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Hearings -->
    <div class="row mt-5">
        <div class="col">
            <h4>Today's Hearings</h4>
            @if($todayHearings->count())
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Case Number</th>
                            <th>Judge Name</th>
                            <th>Remarks</th>
                            <th>Time</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayHearings as $hearing)
                        <tr>
                            <td>{{ $hearing->case->case_number ?? 'N/A' }}</td>
                            <td>{{ $hearing->judge_name }}</td>
                            <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($hearing->next_hearing)->format('h:i A') }}</td>
                            <td>
                                @if($hearing->priority === 'important')
                                    <span class="badge bg-danger">Important</span>
                                @else
                                    <span class="badge bg-secondary">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No hearings scheduled for today.</p>
            @endif
        </div>
    </div>

    <!-- Tomorrow's Hearings -->
    <div class="row mt-4">
        <div class="col">
            <h4>Tomorrow's Hearings</h4>
            @if($tomorrowHearings->count())
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Case Number</th>
                            <th>Judge Name</th>
                            <th>Remarks</th>
                            <th>Time</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tomorrowHearings as $hearing)
                        <tr>
                            <td>{{ $hearing->case->case_number ?? 'N/A' }}</td>
                            <td>{{ $hearing->judge_name }}</td>
                            <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($hearing->next_hearing)->format('h:i A') }}</td>
                            <td>
                                @if($hearing->priority === 'important')
                                    <span class="badge bg-danger">Important</span>
                                @else
                                    <span class="badge bg-secondary">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No hearings scheduled for tomorrow.</p>
            @endif
        </div>
    </div>
</div>
@endsection
