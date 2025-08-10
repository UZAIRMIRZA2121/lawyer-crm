@extends('layouts.app')

@section('content')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
            <div class="col-md-3">
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
            </h4>

            <table class="table table-bordered mb-4">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Submit Date</th>
                        <th>Status</th>
                        <th>Sub Status</th>
                        <th>Actions</th>
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
                            <td>
                                      <button class="btn btn-sm btn-info view-task-btn" id="view-task-btn"
                                data-task="{{ htmlspecialchars($task->task) }}" data-user="{{ $task->user->name }}"
                                data-priority="{{ ucfirst($task->priority) }}" data-date="{{ $task->submit_date }}"
                                data-status="{{ ucfirst($task->status) }}">
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
        @endforeach

    </div>


    <!-- Modal -->
    <div class="modal fade" id="taskDetailModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="taskDetailModalLabel">Task Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div><strong>Task:</strong></div>
                    <div id="modalTaskContent" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
