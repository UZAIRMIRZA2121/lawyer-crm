@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Draft Items</h1>
        <form method="GET" action="{{ route('draft.index') }}" class="mb-4">
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

                <!-- Status Filter -->
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php
                            $statuses = ['pending' => 'Pending', 'done' => 'Done'];
                        @endphp
                        <a href="{{ route('draft.index', array_merge(request()->except(['page', 'status']), ['status' => null])) }}"
                            class="btn btn-sm {{ request('status') === null ? 'btn-primary' : 'btn-outline-primary' }}">
                            All
                        </a>
                        @foreach ($statuses as $key => $label)
                            <a href="{{ route('draft.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                                class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4 mt-5">
                    <button class="btn btn-dark mb-3" onclick="printAllTables()">🖨 Print All Tables</button>
                </div>
            </div>
        </form>

        {{-- Draft Cases --}}
        <h2>Draft Cases</h2>
        @if ($draftCases->isEmpty())
            <p>No draft cases found.</p>
        @else
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-striped align-middle table-fixed-header">
                    <thead class="table-light">
                        <tr>
                            <th>Case Number</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Next Hearing</th>
                            <th>Judge</th>
                            <th>Assigned Users</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($draftCases as $case)
                            <tr>
                                <td>{{ $case->case_number }}</td>
                                <td>{{ $case->client->name ?? 'N/A' }}</td>
                                <td>{{ $case->case_title }}</td>
                                <td>{{ ucfirst($case->status) }}</td>
                                <td>
                                    @php
                                        $nextHearing = $case->hearings->first();
                                    @endphp
                                    @if ($nextHearing)
                                        {{ \Carbon\Carbon::parse($nextHearing->next_hearing)->format('d M Y, h:i A') }}
                                    @else
                                        <span class="text-muted">No hearing</span>
                                    @endif
                                </td>
                                <td>{{ $case->judge_name ?? 'N/A' }}</td>
                                <td>
                                    @if ($case->assignedUsers->count())
                                        @foreach ($case->assignedUsers as $user)
                                            <span class="badge bg-primary">{{ $user->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No assigned users</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('cases.show', $case) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('cases.edit', $case) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('cases.destroy', $case) }}" method="POST"
                                        onsubmit="return confirm('Delete this case?')" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Draft Tasks --}}
        <h2>Draft Tasks</h2>
        @if ($draftTasks->isEmpty())
            <p>No draft tasks found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Task</th>
                            <th>Submit Date</th>
                            <th>Status</th>
                            <th>Sub Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($draftTasks as $task)
                            <tr>
                                <td>{{ $task->user->name ?? 'N/A' }}</td>
                                <td>{!! \Illuminate\Support\Str::limit(strip_tags($task->task), 30) !!}</td>
                                <td>{{ $task->submit_date }}</td>
                                <td>
                                    @php
                                        $status = strtolower($task->status);
                                        $badgeClass = match ($status) {
                                            'pending' => 'bg-warning text-dark',
                                            'working' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td>
                                    @php
                                        $subStatus = strtolower($task->sub_status);
                                        $subStatusClass = match ($subStatus) {
                                            'drafting' => 'bg-info text-white',
                                            'research' => 'bg-success text-white',
                                            'note' => 'bg-danger text-white',
                                            default => 'bg-light text-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $subStatusClass }}">{{ ucfirst($subStatus) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info view-task-btn"
                                        data-task="{{ htmlspecialchars($task->task) }}"
                                        data-user="{{ $task->user->name }}"
                                        data-priority="{{ ucfirst($task->priority) }}"
                                        data-date="{{ $task->submit_date }}"
                                        data-status="{{ ucfirst($task->status) }}">
                                        View
                                    </button>
                                    <div class="d-none task-html">{!! $task->task !!}</div>

                                    @if (auth()->user()->id === $task->user_id || auth()->user()->role === 'admin')
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                            class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-muted">No Access</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        function printAllTables() {
            let tables = document.querySelectorAll('.container table');
            if (tables.length === 0) {
                alert('No tables found to print.');
                return;
            }
            let printWindow = window.open('', '', 'width=900,height=700');
            let styles = `
                <style>
                    @page { size: A4; margin: 10mm; }
                    body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-after: auto; }
                    table, th, td { border: 1px solid #000; }
                    th, td { padding: 8px; text-align: left; }
                    thead { background-color: #f2f2f2; }
                    h2 { margin-top: 30px; margin-bottom: 10px; }
                </style>
            `;
            printWindow.document.write('<html><head><title>Print Draft Tables</title>');
            printWindow.document.write(styles);
            printWindow.document.write('</head><body>');

            tables.forEach(function(table) {
                let clonedTable = table.cloneNode(true);
                let headers = clonedTable.querySelectorAll('thead th');
                let actionIndex = -1;
                headers.forEach((th, i) => {
                    if (th.innerText.trim().toLowerCase() === 'actions') {
                        actionIndex = i;
                    }
                });
                if (actionIndex > -1) {
                    clonedTable.querySelectorAll('tr').forEach(row => {
                        if (row.children[actionIndex]) {
                            row.removeChild(row.children[actionIndex]);
                        }
                    });
                }
                let heading = table.closest('.table-responsive')?.previousElementSibling;
                if (heading && heading.tagName === 'H2') {
                    printWindow.document.write('<h2>' + heading.innerText + '</h2>');
                }
                printWindow.document.write(clonedTable.outerHTML);
            });

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // View Task Modal
        $(document).on('click', '.view-task-btn', function() {
            const row = $(this).closest('tr');
            $('#modalUser').text($(this).data('user'));
            $('#modalPriority').text($(this).data('priority'));
            $('#modalDate').text($(this).data('date'));
            $('#modalStatus').text($(this).data('status'));
            $('#modalTaskContent').html(row.find('.task-html').html());
            $('#taskDetailModal').modal('show');
        });
    </script>
@endsection
