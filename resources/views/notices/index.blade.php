@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Notices</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('notices.create') }}" class="btn btn-primary mb-3">Add Notice</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Case</th>
                <th>User</th>
                <th>Against Client ID</th>
                <th>Notice</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notices as $notice)
                <tr>
                    <td>{{ $notice->case->case_title ?? 'N/A' }}</td>
                    <td>{{ $notice->user->name ?? 'N/A' }}</td>
                    <td>{{ $notice->against_client->name ?? 'N/A' }}</td>
                    <td>{{ $notice->notice }}</td>
                    <td>{{ $notice->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('notices.edit', $notice) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('notices.destroy', $notice) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this notice?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No notices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
