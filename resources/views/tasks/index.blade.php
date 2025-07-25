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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Submit Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->user->name ?? 'N/A' }}</td>
                        <td>{!! Str::limit(strip_tags($task->task), 30) !!}</td>
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
                            <button class="btn btn-sm btn-info view-task-btn " id="view-task-btn"
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
                @empty
                    <tr>
                        <td colspan="6">No tasks found.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
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
