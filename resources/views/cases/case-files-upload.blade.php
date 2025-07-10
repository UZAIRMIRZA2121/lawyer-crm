@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Files for Case: {{ $case->title }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('case_files.store', $case->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Upload File</label>
            <input type="file" name="file" class="form-control" required>
            @error('file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload File</button>
    </form>

    <hr>

    <h4>Uploaded Files</h4>
    @if($files->count())
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File</th>
                    <th>Uploaded By</th>
                    <th>Date</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>{{ $file->sequence }}</td>
                        <td>{{ basename($file->file_path) }}</td>
                        <td>{{ $file->user->name }}</td>
                        <td>{{ $file->created_at->format('d-m-Y h:i A') }}</td>
                        <td>
                            <a href="{{ asset('public/storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-success">Download</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No files uploaded yet.</p>
    @endif
</div>
@endsection
