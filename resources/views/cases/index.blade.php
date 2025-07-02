@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Cases List</h1>

        <a href="{{ route('cases.create') }}" class="btn btn-primary mb-3">Add New Case</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Case Number</th>
                        <th>Client</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Hearing Date</th>
                        <th>Judge</th>
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
                                @if ($case->hearing_date)
                                    {{ \Carbon\Carbon::parse($case->hearing_date)->format('l, d-m-Y h:i A') }}
                                @else
                                    N/A
                                @endif
                            </td>



                            <td>{{ $case->judge_name ?? 'N/A' }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('cases.show', $case) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('cases.edit', $case) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('cases.destroy', $case) }}" method="POST"
                                        onsubmit="return confirm('Delete this case?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                    </form>
                                    <a href="{{ route('case.files.create', $case) }}" class="btn btn-primary btn-sm">
                                        Upload Files
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No cases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cases->links() }}
        </div>
    </div>
@endsection
