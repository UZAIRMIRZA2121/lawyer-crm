@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Upload Files for Case: {{ $case->case_title }}</h3>

        <form action="{{ route('case.files.store', $case) }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="mb-3">
                <input type="file" name="files[]" class="form-control" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>

        </form>
@if($all_case_files->count())
    <hr>
    <h4 class="mt-4">Uploaded Files Gallery</h4>
    <div class="row">
        @foreach($all_case_files as $file)
            <div class="col-md-3 mb-3">
                <div class="card">
                    @php
                        $fileUrl = asset('storage/' . $file->file_path);
                        $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                    @endphp

                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                        <img src="{{ $fileUrl }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="text-center p-5">
                            <i class="bi bi-file-earmark-text fs-1"></i>
                            <p>{{ strtoupper($ext) }} file</p>
                        </div>
                    @endif

                    <div class="card-body p-2 text-center d-flex justify-content-center gap-2">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            View
                        </a>

                       <form action="{{ route('files.destroy', $file) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this file?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="mt-3">No files uploaded yet.</p>
@endif


    </div>
@endsection
