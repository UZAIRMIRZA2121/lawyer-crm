@extends('layouts.app')

@section('content')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @media print {

            /* Hide the Actions column in print view */
            th.actions-column,
            td.actions-column {
                display: none !important;
            }

            table {
                font-size: 16px;
                font-weight: 600;
            }

            table,
            th,
            td {
                border: 1px solid #000 !important;
            }


            /* Optional: hide any buttons or links not needed in print */
            .btn {
                display: none !important;
            }
        }

        #modalTask {
            word-wrap: break-word;
            /* break long words */
            overflow-wrap: break-word;
            /* fallback */
            white-space: normal;
            /* prevent preformatted white-space */
        }
    </style>
    <div class="container">
        <h2>Tasks</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'team')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>
        @endif
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-3" id="filterFormTasks">
            <div class="row g-3 align-items-end mb-4">

                <!-- Search (full width on xs, half on md+) -->
                <div class="col-12 col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by Task Name, Description, etc." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">🔍</button>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="col-12 col-md-2">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                <!-- End Date -->
                <div class="col-12 col-md-2">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>

                <!-- Filter Button -->
                <div class="col-6 col-md-1">
                    <button type="submit" form="filterFormTasks" class="btn btn-primary w-100">Filter</button>
                </div>

                <!-- Reset Button -->
                <div class="col-6 col-md-1">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="row g-3 align-items-end mb-4">
            <!-- Priority Filter for Tasks -->
            <div class="col-md-3">
                <label class="form-label">Priority</label>
                <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('tasks.index', array_merge(request()->except('page', 'priority'), ['priority' => null])) }}"
                        class="btn btn-sm {{ request('priority') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @php
                        $priorityFilters = [
                            'urgent' => 'Urgent',
                            'important' => 'Important',
                            'normal' => 'Normal',
                        ];
                    @endphp
                    @foreach ($priorityFilters as $key => $label)
                        <a href="{{ route('tasks.index', array_merge(request()->except('page'), ['priority' => $key])) }}"
                            class="btn btn-sm {{ request('priority') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Status Filter for Tasks -->
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('tasks.index', array_merge(request()->except('page', 'status'), ['status' => null])) }}"
                        class="btn btn-sm {{ request('status') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @php
                        $statusFilters = [
                            'pending' => 'Pending',
                            'done' => 'Done',
                        ];
                    @endphp
                    @foreach ($statusFilters as $key => $label)
                        <a href="{{ route('tasks.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sub Status</label>
                <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('tasks.index', array_merge(request()->except('page', 'sub_status'), ['sub_status' => null])) }}"
                        class="btn btn-sm {{ request('sub_status') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @php
                        $subStatusFilters = [
                            'drafting' => 'Drafting',
                            'research' => 'Research',
                            'note' => 'Note',
                            'preparation' => 'Preparation',
                        ];
                    @endphp
                    @foreach ($subStatusFilters as $key => $label)
                        <a href="{{ route('tasks.index', array_merge(request()->except('page'), ['sub_status' => $key])) }}"
                            class="btn btn-sm {{ request('sub_status') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Print All Tasks</label>
                <div class="d-flex flex-wrap gap-1">
                    <!-- Print All Tasks Button -->
                    <button type="button" class="btn btn-sm btn-primary " id="print-all-tasks-btn">
                        Print All Tasks
                    </button>


                </div>
            </div>


        </div>

        @foreach ($tasks as $groupId => $groupTasks)
            @php
                // Get the title from the first task in the group (assuming all tasks in group share same title)
                $groupTitle = $groupTasks->first()->title ?? 'No Title';
            @endphp

            <h4>
                Title: {{ $groupTitle }}
                <form action="{{ route('tasks.destroyGroup', $groupId) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete this entire group?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger ms-2">Delete Group</button>
                </form>
                <!-- Print Group Button -->
                <button type="button" class="btn btn-sm btn-primary ms-2 print-group-btn"
                    data-group-id="{{ $groupId }}" data-group-title="{{ $groupTitle }}">
                    Print Group
                </button>

            </h4>

            <table class="table table-bordered mb-4 group-table" id="group-table-{{ $groupId }}">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Submit Date</th>
                        <th>Status</th>
                        <th>Sub Status</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupTasks as $task)
                        <tr>
                            <td>{{ $task->user->name ?? 'N/A' }}</td>
                            <td>{!! \Illuminate\Support\Str::limit(strip_tags($task->task), 30) !!}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
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
                            <td class="actions-column">
                                <!-- Button -->
                                <button class="btn btn-sm btn-info view-task-btn" data-task-id="{{ $task->id }}"
                                    data-user="{{ $task->user->name }}" data-priority="{{ ucfirst($task->priority) }}"
                                    data-date="{{ $task->submit_date }}" data-status="{{ ucfirst($task->status) }}">
                                    View
                                </button>

                                <!-- Hidden HTML container -->
                                <div id="task-content-{{ $task->id }}" class="d-none">
                                    {!! $task->task !!}
                                </div>

                                <!-- Print Button -->
                                <button class="btn btn-sm btn-secondary print-task-btn" data-title="{{ $task->title }}"
                                    data-task="{{ htmlspecialchars($task->task) }}" data-user="{{ $task->user->name }}"
                                    data-priority="{{ ucfirst($task->priority) }}" data-date="{{ $task->submit_date }}"
                                    data-status="{{ ucfirst($task->status) }}">
                                    Print
                                </button>

                                <!-- Hidden element with full HTML task -->
                                <div class="d-none task-html">{!! $task->task !!}</div>

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
        @endforeach

    </div>


    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Task:</strong></p>
                    <div id="modalTask" class="border p-2 rounded bg-light"></div>

                    <hr>
                    <p><strong>User:</strong> <span id="modalUser"></span></p>
                    <p><strong>Priority:</strong> <span id="modalPriority"></span></p>
                    <p><strong>Submit Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("print-all-tasks-btn").addEventListener("click", function() {
                // Get all tables with class group-table
                let tables = document.querySelectorAll(".group-table");
                if (!tables.length) {
                    alert("No tables found!");
                    return;
                }

                let combinedHtml = '<h2 style="text-align:center;">All Tasks</h2>';

                tables.forEach(table => {
                    // Clone each table
                    combinedHtml +=
                        '<table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse; margin-bottom:20px; font-family: Arial, sans-serif; font-size:14px;">';
                    combinedHtml += table.querySelector('thead').outerHTML;
                    combinedHtml += table.querySelector('tbody').outerHTML;
                    combinedHtml += '</table>';
                });

                // Open new window for printing
                let printWindow = window.open("", "_blank");
                printWindow.document.write(`
            <html>
                <head>
                    <title>Print All Tasks</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                        th { background: #f2f2f2; }
                        .actions-column { display: none; } /* Hide action buttons */

                          table{
                font-size: 16px ;
                font-weight: 600;
            }
            table,
            th,
            td {
                border: 1px solid #000 !important;
            }
                    </style>
                </head>
                <body>
                    ${combinedHtml}
                    <script>window.print(); window.close();<\/script>
                </body>
            </html>
        `);
                printWindow.document.close();
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".view-task-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                    let taskId = this.getAttribute("data-task-id");
                    let taskHtml = document.getElementById("task-content-" + taskId).innerHTML;

                    document.getElementById("modalTask").innerHTML = taskHtml;
                    document.getElementById("modalUser").textContent = this.getAttribute(
                        "data-user");
                    document.getElementById("modalPriority").textContent = this.getAttribute(
                        "data-priority");
                    document.getElementById("modalDate").textContent = this.getAttribute(
                        "data-date");
                    document.getElementById("modalStatus").textContent = this.getAttribute(
                        "data-status");

                    let modal = new bootstrap.Modal(document.getElementById("taskModal"));
                    modal.show();
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle Print Group Button
            document.querySelectorAll(".print-group-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let groupId = this.getAttribute("data-group-id");
                    let groupTitle = this.getAttribute("data-group-title");
                    let table = document.getElementById("group-table-" + groupId);

                    if (!table) {
                        alert("Table not found for this group!");
                        return;
                    }

                    // Clone the table for printing
                    let printContent = `
                <h2 style="text-align:center;">Group: ${groupTitle}</h2>
                <table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse; font-family: Arial, sans-serif; font-size: 14px;">
                    ${table.innerHTML}
                </table>
            `;

                    let printWindow = window.open("", "_blank");
                    printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Group - ${groupTitle}</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                            th { background: #f2f2f2; }
                            .actions-column { display: none; } /* Hide action buttons */

                              table{
                font-size: 16px ;
                font-weight: 600;
            }
            table,
            th,
            td {
                border: 1px solid #000 !important;
            }
                        </style>
                    </head>
                    <body>
                        ${printContent}
                        <script>window.print(); window.close();<\/script>
                    </body>
                </html>
            `);
                    printWindow.document.close();
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Print single task
            document.querySelectorAll(".print-task-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    const row = btn.closest('tr');
                    const taskContent = row.querySelector('.task-html').innerHTML;

                    // Fetch data from attributes
                    const title = btn.getAttribute('data-title') || 'Task Details';
                    const user = btn.getAttribute('data-user');
                    const priority = btn.getAttribute('data-priority');
                    const date = btn.getAttribute('data-date');
                    const status = btn.getAttribute('data-status');

                    const printWindow = window.open('', '', 'width=800,height=600');
                    printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Task: ${title}</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            h2 { text-align: center; margin-bottom: 20px; }
                            .task-info { margin-bottom: 15px; }
                            .task-info strong { width: 120px; display: inline-block; }
                              table{
                font-size: 16px ;
                font-weight: 600;
            }
            table,
            th,
            td {
                border: 1px solid #000 !important;
            }
                        </style>
                    </head>
                    <body>
                        <h2>${title}</h2>
                        <div class="task-info"><strong>User:</strong> ${user}</div>
                        <div class="task-info"><strong>Priority:</strong> ${priority}</div>
                        <div class="task-info"><strong>Date:</strong> ${date}</div>
                        <div class="task-info"><strong>Status:</strong> ${status}</div>
                        <hr>
                        <div>${taskContent}</div>
                    </body>
                </html>
            `);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                });
            });
        });
    </script>
@endsection
