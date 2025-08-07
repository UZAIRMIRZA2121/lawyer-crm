@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($case)
            <h4>Hearings for Case: {{ $case->case_number }}</h4>
        @else
            <h4>All Hearings</h4>
        @endif


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($case)
            <a href="{{ route('hearings.create', ['case_id' => request()->query('case_id') ?? $case->id]) }}"
                class="btn btn-success btn-sm">
                Add Hearing
            </a>
        @endif

        <!-- Filters -->
        <div class="row mb-3">
            <!-- Priority Filter -->
            <div class="col-md-6">
                <label class="form-label">Priority</label>
                <div class="d-flex flex-wrap gap-1">
                    @php
                        $priorities = ['normal' => 'Normal', 'urgent' => 'Urgent', 'important' => 'Important'];
                    @endphp
                    <a href="{{ route('hearings.index', array_merge(request()->except(['page', 'priority']), ['priority' => null])) }}"
                        class="btn btn-sm {{ request('priority') === null ? 'btn-primary' : 'btn-outline-primary' }}">
                        All
                    </a>
                    @foreach ($priorities as $key => $label)
                        <a href="{{ route('hearings.index', array_merge(request()->except('page'), ['priority' => $key])) }}"
                            class="btn btn-sm {{ request('priority') === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <div class="d-flex flex-wrap gap-1">
                    @php
                        $statuses = ['pending' => 'Pending', 'done' => 'Done'];
                    @endphp
                    <a href="{{ route('hearings.index', array_merge(request()->except(['page', 'status']), ['status' => null])) }}"
                        class="btn btn-sm {{ request('status') === null ? 'btn-primary' : 'btn-outline-primary' }}">
                        All
                    </a>
                    @foreach ($statuses as $key => $label)
                        <a href="{{ route('hearings.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>


        @if ($hearings->count())
            <table class="table table-bordered m-3">
                <thead>
                    <tr>
                        <th>Judge Name</th>
                        <th>Judge Remarks</th>
                        <th>My Remarks</th>
                        <th>Next Hearing</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Nature</th>
                      
                            <th>Actions</th>
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hearings as $hearing)
                        <tr>
                            <td>{{ $hearing->judge_name }}</td>
                            <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                            <td>{{ $hearing->my_remarks ?? 'N/A' }}</td>
                            <td>{{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('d-m-Y h:i A') : 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $priorityClasses = [
                                        'important' => 'badge bg-danger',
                                        'urgent' => 'badge bg-warning text-dark',
                                        'normal' => 'badge bg-success',
                                    ];
                                    $priority = $hearing->priority ?? 'normal';
                                    $priorityClass = $priorityClasses[$priority] ?? 'badge bg-secondary';
                                @endphp

                                <span class="{{ $priorityClass }}">{{ ucfirst($priority) }}</span>
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

                            <td>{{ $hearing->nature ?? 'N/A' }}</td>
                           
                                <td>

                                    <a href="{{ route('hearings.edit', $hearing) }}?case_id={{ $case->id ?? $hearing->case_id }}"
                                        class="btn btn-warning btn-sm">Edit</a>


                                    <form action="{{ route('hearings.destroy', $hearing) }}?case_id={{ $case->id ?? $hearing->case_id }}"
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

            {{ $hearings->links() }}
        @else
            <p>No hearings found.</p>
        @endif
    </div>
@endsection
