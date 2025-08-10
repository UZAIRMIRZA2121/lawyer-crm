@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Urgent Items</h1>
        <form method="GET" action="{{ route('urgent.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search keyword..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        {{-- Urgent Notices --}}
        <h2>Urgent Notices</h2>
        @if ($urgentNotices->isEmpty())
            <p>No urgent notices found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Notice</th>
                            <th>Case Title</th>
                            <th>Against Client</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentNotices as $notice)
                            <tr>
                                <td>{{ $notice->notice ?? '-' }}</td>
                                <td>{{ $notice->case->case_title ?? 'N/A' }}</td>
                                <td>{{ $notice->against_client->name ?? 'N/A' }}</td>
                                <td>{{ $notice->user->name ?? '-' }}</td>
                                <td>{{ ucfirst($notice->status) ?? '-' }}</td>
                                <td>{{ ucfirst($notice->priority) ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Urgent Cases --}}
        <h2>Urgent Cases</h2>
        @if ($urgentCases->isEmpty())
            <p>No urgent cases found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Case Number</th>
                            <th>Case Title</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Hearing Date</th>
                            <th>Judge Name</th>
                            <th>Case Nature</th>
                            <th>Amount</th>
                            <th>Commission Amount</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentCases as $case)
                            <tr>
                                <td>{{ $case->case_number ?? '-' }}</td>
                                <td>{{ $case->case_title ?? '-' }}</td>
                                <td>{{ $case->client->name ?? '-' }}</td>
                                <td>{{ ucfirst($case->status) ?? '-' }}</td>
                                <td>{{ ucfirst($case->priority) ?? '-' }}</td>
                                <td>{{ $case->hearing_date ? \Carbon\Carbon::parse($case->hearing_date)->format('Y-m-d') : '-' }}
                                </td>
                                <td>{{ $case->judge_name ?? '-' }}</td>
                                <td>{{ $case->case_nature ?? '-' }}</td>
                                <td>{{ $case->amount ?? '-' }}</td>
                                <td>{{ $case->commission_amount ?? '-' }}</td>
                                <td>{{ $case->created_at ? $case->created_at->format('Y-m-d') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Urgent Tasks --}}
        <h2>Urgent Tasks</h2>
        @if ($urgentTasks->isEmpty())
            <p>No urgent tasks found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>User</th>
                            <th>Priority</th>
                            <th>Submit Date</th>
                            <th>Status</th>
                            <th>Sub Status</th>
                            <th>Group ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentTasks as $task)
                            <tr>
                                <td>{{ $task->title ?? '-' }}</td>
                                <td>{{ $task->user->name ?? '-' }}</td>
                                <td>{{ ucfirst($task->priority) ?? '-' }}</td>
                                <td>{{ $task->submit_date ? \Carbon\Carbon::parse($task->submit_date)->format('Y-m-d') : '-' }}
                                </td>
                                <td>{{ ucfirst($task->status) ?? '-' }}</td>
                                <td>{{ $task->sub_status ?? '-' }}</td>
                                <td>{{ $task->group_id ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Urgent Hearings --}}
        <h2>Urgent Hearings</h2>
        @if ($urgentHearings->isEmpty())
            <p>No urgent hearings found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Judge Name</th>
                            <th>Judge Remarks</th>
                            <th>My Remarks</th>
                            <th>Next Hearing</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Nature</th>
                            <th>Case Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentHearings as $hearing)
                            <tr>
                                <td>{{ $hearing->judge_name ?? '-' }}</td>
                                <td>{{ $hearing->judge_remarks ?? '-' }}</td>
                                <td>{{ $hearing->my_remarks ?? '-' }}</td>
                                <td>{{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('Y-m-d') : '-' }}
                                </td>
                                <td>{{ ucfirst($hearing->status) ?? '-' }}</td>
                                <td>{{ ucfirst($hearing->priority) ?? '-' }}</td>
                                <td>{{ $hearing->nature ?? '-' }}</td>
                                <td>{{ $hearing->case->case_title ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
