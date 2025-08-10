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

    <div class="container py-4">
        <h1 class="mb-4">Cases List {{ $cases->count() }}</h1>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('cases.create') }}" class="btn btn-primary mb-3">Add New Case</a>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="container">
  <form method="GET" action="{{ route('cases.index') }}" id="filterForm">
    <div class="row g-3 align-items-end mb-4">
      
      <!-- Search (full width on xs, half on md+) -->
      <div class="col-12 col-md-6">
        <label for="search" class="form-label">Search</label>
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search by Case Number, Title, etc." value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary">🔍</button>
        </div>
      </div>

      <!-- Start Date -->
      <div class="col-12 col-md-2">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
      </div>

      <!-- End Date -->
      <div class="col-12 col-md-2">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
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

    <hr>

    <div class="row g-3 align-items-center">

      <!-- Priority Filter -->
      <div class="col-12 col-md-6">
        <label class="form-label">Priority</label>
        <div class="d-flex flex-wrap gap-1">
          <a href="{{ route('cases.index', array_merge(request()->except('page', 'priority'), ['priority' => null])) }}" class="btn btn-sm {{ request('priority') === null ? 'btn-primary' : '' }}">
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
              <a href="{{ route('cases.index', array_merge(request()->except('page'), ['priority' => $key])) }}" class="btn btn-sm {{ request('priority') === $key ? 'btn-primary' : '' }}">
                {{ $label }}
              </a>
          @endforeach
        </div>
      </div>

      <!-- Status Filter -->
      <div class="col-12 col-md-4">
        <label class="form-label">Status</label>
        <div class="d-flex flex-wrap gap-1">
          <a href="{{ route('cases.index', array_merge(request()->except('page', 'status'), ['status' => null])) }}" class="btn btn-sm {{ request('status') === null ? 'btn-primary' : '' }}">
            All
          </a>
          @php
              $subFilters = [
                  'pending' => 'Pending',
                  'done' => 'Done',
              ];
          @endphp
          @foreach ($subFilters as $key => $label)
              <a href="{{ route('cases.index', array_merge(request()->except('page'), ['status' => $key])) }}" class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : '' }}">
                {{ $label }}
              </a>
          @endforeach
        </div>
      </div>

      <!-- Print Button -->
      <div class="col-12 col-md-2 d-flex justify-content-md-end">
        <button type="button" class="btn btn-outline-dark w-100 w-md-auto" onclick="printTable()">🖨️ Print Table</button>
      </div>

    </div>
  </form>
</div>
<hr>
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
                    @forelse($cases as $case)
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
                                   {{$nextHearing->nature}}
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

        <div class="mt-3">
            {{ $cases->links() }}
        </div>
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
