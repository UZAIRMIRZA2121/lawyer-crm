@extends('layouts.app')

@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required by Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div class="container py-4">
        <h1 class="mb-4">Add New Case</h1>

        <form action="{{ route('cases.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Case Number</label>
                    <input type="text" name="case_number" class="form-control" value="{{ old('case_number') }}" required>
                    @error('case_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Client</label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="" disabled selected>Select a client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Case Title</label>
                    <input type="text" name="case_title" class="form-control" value="{{ old('case_title') }}" required>
                    @error('case_title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Case Nature</label>
                    <input type="text" name="case_nature" class="form-control" value="{{ old('case_nature') }}" required>
                    @error('case_nature')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @php
                            $statuses = ['open' => 'Open', 'pending' => 'Pending', 'closed' => 'Closed'];
                        @endphp
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Judge Name</label>
                    <input type="text" name="judge_name" class="form-control" value="{{ old('judge_name') }}">
                    @error('judge_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @if (Auth::user()->role == 'admin')
                    <div class="col-md-6">
                        <label class="form-label">Total Amount</label>
                        <input type="text" name="amount" class="form-control" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            </div>
            {{-- Summernote CSS --}}
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea id="summernote" name="description" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Summernote JS --}}
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

            {{-- Initialize Summernote --}}
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    $('#summernote').summernote({
                        height: 200, // Set editor height
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });
                });
            </script>


            <button class="btn btn-success" type="submit">Save Case</button>
            <a href="{{ route('cases.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>


    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <!-- Popper.js (required for Bootstrap 4 dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    {{-- Optional JavaScript to link client_name to client_id --}}
    <script>
        $(document).ready(function() {
            $('#client_id').select2({
                placeholder: "Select a client",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
