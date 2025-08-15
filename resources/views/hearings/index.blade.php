@extends('layouts.app')

@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .table-responsive,
            .table-responsive * {
                visibility: visible;
            }

            .table-responsive {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
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
            <form method="GET" action="{{ route('hearings.index') }}" class="mb-3" id="filterForm">
                <div class="row g-3 align-items-end mb-4">

                    <!-- Search (full width on xs, half on md+) -->
                    <div class="col-12 col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by Case Number, Title, etc." value="{{ request('search') }}">
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
                        <button type="submit" form="filterForm" class="btn btn-primary w-100">Filter</button>

                    </div>

                    <!-- Reset Button -->
                    <div class="col-6 col-md-1">
                        <a href="{{ route('cases.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>

            </form>

            <!-- Priority Filter -->
            <div class="col-md-4">
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
            <div class="col-md-4">
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
            <!-- Print Button -->
            <!-- Print Button -->
            <div class="col-12 col-md-2 d-flex justify-content-md-end">
                <button type="button" class="btn btn-outline-dark w-100 w-md-auto" onclick="printTable()">🖨️ Print
                    Table</button>
            </div>

        </div>



        @if ($hearings->count())
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-striped align-middle table-fixed-header">
                    <thead class="table-light">
                        <tr>
                            <th>Case #</th>
                            <th>Case Title</th>
                            <th>Judge Name</th>
                            <th>Judge Remarks</th>
                            <th>My Remarks</th>
                            <th>Next Hearing Date</th>
                            <th>Next Proceeding </th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hearings as $hearing)
                            <tr>
                                <td>{{ $hearing->case->case_number ?? 'N/A' }}</td>
                                <td>{{ $hearing->case->case_title ?? 'N/A' }}</td>
                                <td>{{ $hearing->judge_name ?? 'N/A' }}</td>
                                <td>{{ $hearing->judge_remarks  ?? 'N/A' }}</td>
                                <td>{{ $hearing->my_remarks  ?? 'N/A' }}</td>
                                <td>{{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('d-m-Y h:i A') : 'N/A' }}
                                </td>
                                <td>{{ $hearing->nature ?? 'N/A'}}
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

                                    <span class="{{ $priorityClass }}">{{ ucfirst($priority)  }}</span>
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

            {{-- {{ $hearings->links() }} --}}
        @else
            <p>No hearings found.</p>
        @endif
    </div>

    <script>
        function printTable() {
            // Hide the Actions column before printing
            const actionColIndexes = [];
            const ths = document.querySelectorAll('table thead th');
            ths.forEach((th, index) => {
                if (th.innerText.trim().toLowerCase() === 'actions') {
                    actionColIndexes.push(index);
                }
            });

            // Hide Action column cells
            const rows = document.querySelectorAll('table tr');
            rows.forEach(row => {
                actionColIndexes.forEach(i => {
                    if (row.children[i]) {
                        row.children[i].style.display = 'none';
                    }
                });
            });

            // Trigger print
            window.print();

            // Restore Action column after printing
            setTimeout(() => {
                rows.forEach(row => {
                    actionColIndexes.forEach(i => {
                        if (row.children[i]) {
                            row.children[i].style.display = '';
                        }
                    });
                });
            }, 1000);
        }
    </script>

@endsection
