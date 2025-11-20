@extends('layouts.app')

@section('content')
    <style>
        /* PRINT ONLY TABLE */
        @media print {

            /* Hide everything */
            body * {
                visibility: hidden !important;
            }

            /* Only show table */
            #printArea,
            #printArea * {
                visibility: visible !important;
            }

            /* Expand table fully */
            #printArea {
                position: absolute;
                top: 0;
                left: 0;
                width: 100% !important;
            }

            /* Remove scroll */
            .table-responsive {
                max-height: none !important;
                overflow: visible !important;
            }

            /* Borders & formatting */
            table,
            th,
            td {
                border: 1px solid #000 !important;
                border-collapse: collapse !important;
                font-size: 14px !important;
            }

            th,
            td {
                padding: 6px !important;
            }

            thead {
                display: table-header-group !important;
            }

            /* REMOVE ACTION COLUMN */
            th:last-child,
            td:last-child {
                display: none !important;
            }
        }
    </style>

    <div class="container">

        <h1 class="mb-4">Clients</h1>

        @if (Auth::user()->role == 'admin')
            <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Add Client</a>
        @endif
        <form method="GET" action="{{ route('clients.index') }}" class="mb-3">
            <div class="row g-2">

                {{-- Search Field --}}
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name, CNIC, or Phone"
                        value="{{ request('search') }}">
                </div>

                {{-- Start Date --}}
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                {{-- End Date --}}
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                {{-- Search Button --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
                <div class="col-md-2">
                    <button onclick="printTable()" class="btn btn-primary w-100">Print
                        Table</button>
                </div>


            </div>
        </form>

        <!-- PRINT AREA -->
        <div id="printArea">
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>CNIC</th>
                            <th>Contact No.</th>
                            <th>Email</th>
                            <th>Address</th>
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
        </div>

    </div>

    <script>
        function printTable() {
            window.print();
        }
    </script>
@endsection
