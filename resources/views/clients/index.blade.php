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
        <h1 class="mb-4">Clients</h1>



        @if (Auth::user()->role == 'admin')
            <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Add New Client</a>
        @endif
        <form method="GET" action="{{ route('clients.index') }}" class="mb-3">
            <div class="row g-2">

                {{-- Search Field --}}
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name, CNIC, or Phone"
                        value="{{ request('search') }}">
                </div>

                {{-- Start Date --}}
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                {{-- End Date --}}
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                {{-- Search Button --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>

            </div>
        </form>

        <!-- Print Button -->
        <div class="col-12 col-md-2 d-flex justify-content-md-end">
            <button type="button" class="btn btn-outline-dark w-100 w-md-auto" onclick="printTable()">🖨️ Print
                Table</button>
        </div>

        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-striped align-middle table-fixed-header">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>CNIC</th>
                        <th>Contact No.</th>
                        <th>Email</th>
                        <th>Address</th>
                        {{-- <th>Assigned Users</th> --}}
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->cnic }}</td>
                            <td>{{ $client->contact_no }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->address }}</td>
                            {{-- <td>
                                @foreach ($client->assignedUsers as $user)
                                    <span class="badge bg-primary">{{ $user->name }}</span>
                                @endforeach
                            </td> --}}
                            <td class="text-nowrap">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('cases.index', ['client_id' => $client->id]) }}"
                                    class="btn btn-secondary btn-sm">Cases</a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure?');">
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

        <div class="mt-3">
           
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
