@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3 py-4">
        <h1 class="mb-4">Clients</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Add New Client</a>

        <div class="table-responsive">
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
            {{ $clients->links() }}
        </div>
    </div>
@endsection
