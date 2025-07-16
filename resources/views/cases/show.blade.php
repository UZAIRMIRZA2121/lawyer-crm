@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Case Details</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Case Number:</div>
                    <div class="col-md-9">{{ $case->case_number }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Client:</div>
                    <div class="col-md-9">{{ $case->client->name ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Case Title:</div>
                    <div class="col-md-9">{{ $case->case_title }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Description:</div>
                    <div class="col-md-9">{{ $case->description ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Status:</div>
                    <div class="col-md-9">{{ ucfirst($case->status) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold text-muted">Hearing Date:</div>
                    <div class="col-md-9"> {{ \Carbon\Carbon::parse($case->hearing_date)->format('l, d-m-Y H:i') }}</div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 fw-semibold text-muted">Judge Name:</div>
                    <div class="col-md-9">{{ $case->judge_name ?? 'N/A' }}</div>
                </div>

                <a href="{{ route('cases.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('cases.edit', $case) }}" class="btn btn-primary">Edit</a>
            </div>

        </div>
    </div>
    <div class="container py-4">
        <h1 class="mb-4 ">Uploaded Files Gallery</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($all_case_files->count())
                    <div class="row">
                        @foreach ($all_case_files as $file)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    @php
                                        $fileUrl = asset('storage/' . $file->file_path);
                                        $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                        <img src="{{ $fileUrl }}" class="card-img-top"
                                            style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="text-center p-5">
                                            <i class="bi bi-file-earmark-text fs-1"></i>
                                            <p>{{ strtoupper($ext) }} file</p>
                                        </div>
                                    @endif

                                    <div class="card-body p-2 text-center d-flex justify-content-center gap-2">
                                        <a href="{{ $fileUrl }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
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

        </div>
    </div>

@endsection
