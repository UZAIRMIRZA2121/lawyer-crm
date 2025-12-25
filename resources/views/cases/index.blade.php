@extends('layouts.app')

@section('content')
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }

            body * {
                visibility: hidden;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                overflow: visible !important;
            }

            /* Hide Actions column completely */
            th:nth-last-child(1),
            td:nth-last-child(1) {
                display: none !important;
            }

            /* Table adjustments for print */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 16px;
                font-weight: 600;
                page-break-inside: auto;
            }

            /* Add dark borders to all table cells */
            table,
            th,
            td {
                border: 1px solid #000 !important;
            }

            th {
                background: #f2f2f2;
                /* optional */
                font-weight: bold;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
                border: 1px solid #000 !important;
            }

            /* Optional: style cleanup for print */
            .btn,
            .alert,
            form,
            .row,
            .filter-form,
            a.btn {
                display: none !important;
            }

            h1 {
                margin-bottom: 10px;
            }

        }
    </style>

    <style>
        .custom-dropdown {
            position: relative;
        }

        .custom-dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            min-width: 180px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            display: none;
            z-index: 1000;
            padding: 6px 0;
        }

        .custom-dropdown-menu .dropdown-item {
            padding: 8px 14px;
            display: block;
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }

        .custom-dropdown-menu .dropdown-item:hover {
            background: #f5f5f5;
        }

        .custom-dropdown-menu .dropdown-divider {
            height: 1px;
            background: #e5e5e5;
            margin: 6px 0;
        }

        .custom-dropdown.open .custom-dropdown-menu {
            display: block;
        }
    </style>



    <div class="container py-4">
        <h1 class="mb-4">Cases List {{ $cases->count() }}</h1>


        <a href="{{ route('cases.create') }}" class="btn btn-primary mb-3">Add New Case</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="container">
            <form method="GET" action="{{ route('cases.index') }}" id="filterForm">
                <div class="row g-3 align-items-end mb-4">

                    <!-- Search (full width on xs, half on md+) -->
                    <div class="col-12 col-md-4">
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

                    <!-- Filter Button -->
                    <div class="col-6 col-md-2">
                        <button form="filterForm" onclick="printTable()" class="btn btn-primary w-100">Print</button>
                    </div>
                </div>

                <hr>

                <div class="row g-3 align-items-center">
                    <!-- Priority Filter -->
                    <div class="col-12 col-md-4">
                        <label class="form-label">Priority</label>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('cases.index', array_merge(request()->except('page', 'priority'), ['priority' => null])) }}"
                                class="btn btn-sm {{ request('priority') === null ? 'btn-primary' : '' }}">
                                All
                            </a>
                            @php
                                $filters = [
                                    'urgent' => 'Urgent',
                                    'important' => 'Important',
                                    'normal' => 'Normal',
                                ];
                            @endphp
                            @foreach ($filters as $key => $label)
                                <a href="{{ route('cases.index', array_merge(request()->except('page'), ['priority' => $key])) }}"
                                    class="btn btn-sm {{ request('priority') === $key ? 'btn-primary' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-12 col-md-4">
                        <label class="form-label">Status</label>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('cases.index', array_merge(request()->except('page', 'status'), ['status' => null])) }}"
                                class="btn btn-sm {{ request('status') === null ? 'btn-primary' : '' }}">
                                All
                            </a>
                            @php
                                $subFilters = [
                                    'pending' => 'Pending',
                                    // 'draft' => 'Draft',
                                    'done' => 'Done',
                                ];
                            @endphp
                            @foreach ($subFilters as $key => $label)
                                <a href="{{ route('cases.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                                    class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Sub Status Filter -->
                    <div class="col-12 col-md-2">
                        <label class="form-label">Sub Status</label>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('cases.index', array_merge(request()->except('page', 'sub_status'), ['sub_status' => null])) }}"
                                class="btn btn-sm {{ request('sub_status') === null ? 'btn-primary' : '' }}">
                                All
                            </a>
                            @php
                                $subStatuses = [
                                    'draft' => 'Draft',
                                    'pursue' => 'Pursue',
                                ];
                            @endphp
                            @foreach ($subStatuses as $key => $label)
                                <a href="{{ route('cases.index', array_merge(request()->except('page'), ['sub_status' => $key])) }}"
                                    class="btn btn-sm {{ request('sub_status') === $key ? 'btn-primary' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>




                </div>
            </form>
        </div>
        <hr>
        <div class="table-responsive print-area">
            <table class="table table-bordered table-striped align-middle table-fixed-header">
                <thead class="table-light">
                    <tr>
                        <th>Case Number</th>
                        <th>Client</th>
                        <th>Title</th>
                        {{-- <th>Desc</th> --}}
                        <th>Status</th>
                        <th style="
                            width: 290px;
                        ">Hearing Date</th>
                        <th>Proceeding</th>
                        <th>Judge</th>
                        <th>Nature</th>
                        <th>Assigned Users</th> {{-- New column --}}
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cases as $case)
                        <tr>
                            <td>{{ $case->case_number ?? '' }}</td>
                            <td>{{ $case->client->name ?? '' }}</td>
                            <td>{{ $case->case_title }}</td>
                            {{-- <td>{!! $case->description !!}</td> --}}
                            <td>{{ ucfirst($case->status) }}</td>
                            <td>
                                @php
                                    // Get hearings ordered by date
                                    $hearings = $case->hearings->sortBy('next_hearing')->values();
                                    $firstHearing = $hearings->last();
                                @endphp

                                @if ($firstHearing)
                                    {{ \Carbon\Carbon::parse($firstHearing->next_hearing)->format('d M Y, h:i A') }}
                                @else
                                    <span class="text-muted">No upcoming hearing</span>
                                @endif

                            </td>

                            <td>
                                @php
                                    $nextHearing = $case->hearings->last();
                                @endphp

                                @if ($nextHearing)
                                    {{ $nextHearing->judge_remarks }}
                                @else
                                    <span class="text-muted">No upcoming hearing</span>
                                @endif
                            </td>
                            <td>{{ $case->judge_name ?? 'N/A' }}</td>
                            <td>{{ $case->case_nature ?? 'N/A' }}</td>
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
                                <div class="d-flex align-items-center gap-2 position-relative">

                                    {{-- Always visible --}}
                                    <a href="{{ route('hearings.index', ['case_id' => $case->id]) }}"
                                        class="btn btn-success btn-sm">
                                        View Hearings
                                    </a>

                                    {{-- Extra actions for non-team users --}}
                                    @if (Auth::user()->role !== 'team')
                                        <div class="custom-dropdown">

                                            <button type="button" class="btn btn-secondary btn-sm custom-dropdown-toggle"
                                                onclick="toggleDropdown(this)">
                                                Actions
                                            </button>

                                            <div class="custom-dropdown-menu">

                                                <a class="dropdown-item"
                                                    href="{{ route('case-against-clients.index') }}?case_id={{ $case->id }}">
                                                    Against Client
                                                </a>

                                                <a class="dropdown-item" href="{{ route('cases.show', $case) }}">
                                                    View Case
                                                </a>

                                                <a class="dropdown-item" href="{{ route('cases.edit', $case) }}">
                                                    Edit Case
                                                </a>

                                                <a class="dropdown-item" href="{{ route('cases.files.create', $case) }}">
                                                    Upload Files
                                                </a>

                                                @if (auth()->user()->role === 'admin')
                                                    <a class="dropdown-item"
                                                        href="{{ route('cases.transactions.index', $case) }}">
                                                        Payment
                                                    </a>
                                                @endif

                                                <a class="dropdown-item" href="{{ route('cases.printReport', $case->id) }}"
                                                    target="_blank">
                                                    Print Report
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <form action="{{ route('cases.destroy', $case) }}" method="POST"
                                                    onsubmit="return confirm('Delete this case?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit">
                                                        Delete Case
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    @endif

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


    </div>

    <script>
        function printTable() {
            window.print();
        }
    </script>

    <script>
        function toggleDropdown(button) {
            // Close all other dropdowns
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                if (dropdown !== button.closest('.custom-dropdown')) {
                    dropdown.classList.remove('open');
                }
            });

            // Toggle current
            button.closest('.custom-dropdown').classList.toggle('open');
        }

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });
    </script>


@endsection
