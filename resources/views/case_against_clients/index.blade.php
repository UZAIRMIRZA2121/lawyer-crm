@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Against Clients</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('case-against-clients.create') }}" class="btn btn-info mt-4">
            Add Against Client
        </a>
        <form method="GET" action="" class="m-3">
            @if (request('case_id'))
                <input type="hidden" name="case_id" value="{{ request('case_id') }}">
            @endif
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name, CNIC, or Phone"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Case Title</th>
                        <th>Name</th>
                        <th>CNIC</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $client->case->case_title ?? 'N/A' }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->cnic }}</td>
                            <td>{{ $client->address }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                <a href="{{ route('case-against-clients.edit', $client) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('case-against-clients.destroy', $client) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this client?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No clients found for this case.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">

        </div>
    </div>
@endsection
