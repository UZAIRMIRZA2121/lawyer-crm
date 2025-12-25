@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>User List</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add New User</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-capitalize">{{ str_replace('-', ' ', $user->role) }}</td>

                        {{-- Status Badge --}}
                        <td>
                            @if ($user->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td>{{ $user->contact ?? '-' }}</td>

                        <td>
                            @if ($user->profile_img)
                                <img src="{{ asset('storage/' . $user->profile_img) }}" width="50" class="rounded">
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            </tbody>
        </table>
    </div>
@endsection
