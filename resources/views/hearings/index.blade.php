@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Hearings for Case: {{ $case->case_number }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('hearings.create', ['case_id' => request()->query('case_id') ?? $case->id]) }}"
            class="btn btn-success btn-sm">
            Add Hearing
        </a>


        @if ($hearings->count())
            <table class="table table-bordered m-3">
                <thead>
                    <tr>
                        <th>Judge Name</th>
                        <th>Judge Remarks</th>
                        <th>My Remarks</th>
                        <th>Next Hearing</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hearings as $hearing)
                        <tr>
                            <td>{{ $hearing->judge_name }}</td>
                            <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                            <td>{{ $hearing->my_remarks ?? 'N/A' }}</td>
                            <td>{{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('d-m-Y h:i A') : 'N/A' }}
                            </td>
                            <td
                                @if ($hearing->priority === 'important') style="background-color: #f8d7da; font-weight: bold; color: #721c24;" @endif>
                                {{ ucfirst($hearing->priority) }}
                            </td>
                            <td>
                                <a href="{{ route('hearings.edit', $hearing) }}?case_id={{ $case->id }}"
                                    class="btn btn-warning btn-sm">Edit</a>


                                <form action="{{ route('hearings.destroy', $hearing) }}?case_id={{ $case->id }}"
                                    method="POST" style="display:inline-block"
                                    onsubmit="return confirm('Are you sure to delete this hearing?')">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" name="case_id" value="{{ $case->id }}">

                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $hearings->links() }}
        @else
            <p>No hearings found.</p>
        @endif
    </div>
@endsection
