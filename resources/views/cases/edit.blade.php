@extends('layouts.app')

@section('content')
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">


    <div class="container py-4">
        <h1 class="mb-4">Edit Case</h1>

        <form action="{{ route('cases.update', $case) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Use same inputs as create, but with $case values -->

            <div class="mb-3">
                <label class="form-label">Case Number</label>
                <input type="text" name="case_number" class="form-control"
                    value="{{ old('case_number', $case->case_number) }}" required>
                @error('case_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Client</label>
                <select name="client_id" class="form-select" required>
                    <option value="">-- Select Client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ old('client_id', $case->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Case Title</label>
                <input type="text" name="case_title" class="form-control"
                    value="{{ old('case_title', $case->case_title) }}" required>
                @error('case_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @php
                        $statuses = ['open' => 'Open', 'pending' => 'Pending', 'closed' => 'Closed'];
                    @endphp
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $case->status) == $key ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Hearing Date & Time</label>
                <input type="datetime-local" name="hearing_date" class="form-control"
                    value=" {{ \Carbon\Carbon::parse($case->hearing_date)->format('l, d-m-Y H:i') }}">
                @error('hearing_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">Judge Name</label>
                <input type="text" name="judge_name" class="form-control"
                    value="{{ old('judge_name', $case->judge_name) }}">
                @error('judge_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                @if (Auth::user()->role == 'admin')
                    <div class="col-md-6">
                        <label class="form-label">Total Amount</label>
                        <input type="text" name="amount" class="form-control" value="{{ old('amount' , $case->amount) }}" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea id="summernote" name="description" class="form-control">{{ old('description', $case->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button class="btn btn-success" type="submit">Update Case</button>
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

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['fontname', 'fontsize', 'color']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                fontNames: ['Arial', 'Courier New', 'Comic Sans MS', 'Nunito', 'Times New Roman'],
                popover: {
                    image: [],
                    link: [],
                    air: []
                }
            });
        });
    </script>
@endsection
