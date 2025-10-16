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

                <!-- Status Filter -->
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php
                            $statuses = ['pending' => 'Pending', 'done' => 'Done'];
                        @endphp
                        <a href="{{ route('urgent.index', array_merge(request()->except(['page', 'status']), ['status' => null])) }}"
                            class="btn btn-sm {{ request('status') === null ? 'btn-primary' : 'btn-outline-primary' }}">
                            All
                        </a>
                        @foreach ($statuses as $key => $label)
                            <a href="{{ route('urgent.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                                class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-4 mt-5">
                    <button class="btn btn-dark mb-3" onclick="printAllTables()">🖨 Print All Tables</button>

                </div>


            </div>


        </form>



        {{-- Urgent Notices --}}
        <h2>Urgent Notices</h2>
        @if ($urgentNotices->isEmpty())
            <p>No urgent notices found.</p>
        @else
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-striped align-middle table-fixed-header" id="urgent-tasks-table">
                    <thead class="table-light">
                        <thead>
                            <tr>
                                <th>Case</th>
                                <th>User</th>
                                <th>Against Client</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    <tbody>
                        @forelse ($urgentNotices as $notice)
                            <tr>
                                <td>{{ $notice->case->case_title ?? 'N/A' }}</td>
                                <td>{{ $notice->user->name ?? 'N/A' }}</td>
                                <td>{{ $notice->against_client->name ?? 'N/A' }}</td>

                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'done' => 'success',
                                        ];
                                        $status = strtolower($notice->status);
                                        $statusClass = $statusColors[$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-info btn-view-notice"
                                        data-bs-toggle="modal" data-bs-target="#noticeModal"
                                        data-notice-base64="{{ $notice->notice_base64 }}">
                                        View
                                    </button>

                                    <form action="{{ route('notices.destroy', $notice) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this notice?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No notices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noticeModalLabel">Notice Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="noticeModalBody">
                            <div class="text-center text-muted">Loading...</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Urgent Cases --}}
        <h2>Urgent Cases</h2>
        @if ($urgentCases->isEmpty())
            <p>No urgent cases found.</p>
        @else
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-striped align-middle table-fixed-header">
                    <thead class="table-light">
                        <tr>
                            <th>Case Number</th>
                            <th>Client</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Next Hearing Date</th>
                            <th>Procedure</th>
                            <th>Judge</th>
                            <th>Nature</th>
                            <th>Assigned Users</th> {{-- New column --}}
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urgentCases as $case)
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
                                        <span class="text-muted">No upcoming hearing</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $nextHearing = $case->hearings->first();
                                    @endphp

                                    @if ($nextHearing)
                                        {{ $nextHearing->nature }}
                                    @else
                                        <span class="text-muted">No upcoming hearing</span>
                                    @endif
                                </td>
                                <td>{{ $case->judge_name ?? 'N/A' }}</td>
                                <td>{{ $case->case_nature ?? 'N/A' }}</td>
                                {{-- New Assigned Users column --}}
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
                                    <div class="d-flex flex-wrap gap-1">
                                        <!-- 👇 Add this button -->
                                        <a href="{{ route('case-against-clients.index') }}?case_id={{ $case->id }}"
                                            class="btn btn-secondary btn-sm">
                                            Against Client
                                        </a>
                                        <a href="{{ route('cases.show', $case) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('cases.edit', $case) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('cases.destroy', $case) }}" method="POST"
                                            onsubmit="return confirm('Delete this case?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                        <a href="{{ route('cases.files.create', $case) }}" class="btn btn-primary btn-sm">
                                            Upload Files
                                        </a>

                                        <a href="{{ route('hearings.index', ['case_id' => $case->id]) }}"
                                            class="btn btn-success btn-sm">View Hearings</a>

                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('cases.transactions.index', $case) }}"
                                                class="btn btn-primary btn-sm">
                                                Payment
                                            </a>
                                        @endif
                                        <a href="{{ route('cases.printReport', $case->id) }}" target="_blank"
                                            class="btn btn-dark btn-sm">
                                            Print Report
                                        </a>


                                    </div>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No cases found.</td>
                            </tr>
                        @endforelse
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
                            <th>User</th>
                            <th>Task</th>
                            <th>Submit Date</th>
                            <th>Status</th>
                            <th>Sub Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentTasks as $task)
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
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
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
                                    <span class="badge {{ $subStatusClass }}">
                                        {{ ucfirst($subStatus) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info view-task-btn" id="view-task-btn"
                                        data-task="{{ htmlspecialchars($task->task) }}"
                                        data-user="{{ $task->user->name }}"
                                        data-priority="{{ ucfirst($task->priority) }}"
                                        data-date="{{ $task->submit_date }}" data-status="{{ ucfirst($task->status) }}">
                                        View
                                    </button>
                                    <!-- Hidden element with full HTML task -->
                                    <div class="d-none task-html">{!! $task->task !!}</div>
                                    <script>
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

                                    @if (auth()->user()->id === $task->user_id || auth()->user()->role === 'admin')
                                        <a href="{{ route('tasks.edit', $task->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>

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

        {{-- Urgent Hearings --}}
        <h2>Urgent Hearings</h2>
        @if ($urgentHearings->isEmpty())
            <p>No urgent hearings found.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Case #</th>
                            <th>Case Title</th>
                            <th>Judge Name</th>
                            <th>Current Proceeding</th>
                            <th>My Remarks</th>
                            <th>Next Hearing Date</th>
                            <th>Next Proceeding </th>
                            <th>Status</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urgentHearings as $hearing)
                            <tr>
                                <td>{{ $hearing->case->case_number }}</td>
                                <td>{{ $hearing->case->case_title }}</td>
                                <td>{{ $hearing->judge_name }}</td>
                                <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                                <td>{{ $hearing->my_remarks ?? 'N/A' }}</td>
                                <td>{{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('d-m-Y h:i A') : 'N/A' }}
                                </td>
                                <td>{{ $hearing->nature }}
                                </td>

                                <td>
                                    @if ($hearing->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($hearing->status === 'done')
                                        <span class="badge bg-success">Done</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>


                                <td>

                                    <a href="{{ route('hearings.edit', $hearing) }}?case_id={{ $case->id ?? $hearing->case_id }}"
                                        class="btn btn-warning btn-sm">Edit</a>


                                    <form
                                        action="{{ route('hearings.destroy', $hearing) }}?case_id={{ $case->id ?? $hearing->case_id }}"
                                        method="POST" style="display:inline-block"
                                        onsubmit="return confirm('Are you sure to delete this hearing?')">
                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="case_id" value="{{ $case->id ?? '' }}">

                                        <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                    </form>


                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        function b64_to_utf8(str) {
            return decodeURIComponent(escape(window.atob(str)));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.getElementById('noticeModalBody');

            const noticeModal = document.getElementById('noticeModal');
            noticeModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const base64Content = button.getAttribute('data-notice-base64');

                if (base64Content) {
                    try {
                        const decodedHtml = b64_to_utf8(base64Content);
                        modalBody.innerHTML = decodedHtml;
                    } catch (e) {
                        modalBody.innerHTML = '<p class="text-danger">Failed to load notice content.</p>';
                    }
                } else {
                    modalBody.innerHTML = '<p class="text-muted">No notice content available.</p>';
                }
            });

            noticeModal.addEventListener('hidden.bs.modal', function() {
                modalBody.innerHTML = '<div class="text-center text-muted">Loading...</div>';
            });
        });
    </script>
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
            @page {
                size: A4;
                margin: 10mm;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                page-break-after: auto;
            }
            table, th, td {
                border: 1px solid #000;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            thead {
                background-color: #f2f2f2;
            }
            h2 {
                margin-top: 30px;
                margin-bottom: 10px;
            }
        </style>
    `;

            printWindow.document.write('<html><head><title>Print All Tables</title>');
            printWindow.document.write(styles);
            printWindow.document.write('</head><body>');

            tables.forEach(function(table) {
                let clonedTable = table.cloneNode(true); // Work on a copy so page stays intact

                // Find index of the "Actions" column
                let headers = clonedTable.querySelectorAll('thead th');
                let actionIndex = -1;
                headers.forEach((th, i) => {
                    if (th.innerText.trim().toLowerCase() === 'actions') {
                        actionIndex = i;
                    }
                });

                // Remove the "Actions" header
                if (actionIndex > -1) {
                    clonedTable.querySelectorAll('tr').forEach(row => {
                        if (row.children[actionIndex]) {
                            row.removeChild(row.children[actionIndex]);
                        }
                    });
                }

                // Add section title
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
    </script>

@endsection
