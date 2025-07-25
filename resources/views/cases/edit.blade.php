@extends('layouts.app')

@section('content')
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <div class="container py-4">
        <h1 class="mb-4">Edit Case</h1>

        <form action="{{ route('cases.update', $case) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Case Number</label>
                    <input type="text" name="case_number" class="form-control"
                        value="{{ old('case_number', $case->case_number) }}" required>
                    @error('case_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Select Client --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                {{ old('client_id', $case->client_id) == $client->id ? 'selected' : '' }}>
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
                    <input type="text" name="case_title" class="form-control"
                        value="{{ old('case_title', $case->case_title) }}" required>
                    @error('case_title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Case Nature</label>
                    <input type="text" name="case_nature" class="form-control"
                        value="{{ old('case_nature', $case->case_nature) }}" required>
                    @error('case_nature')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @php
                            $statuses = ['open' => 'Open', 'pending' => 'Pending', 'closed' => 'Closed'];
                        @endphp
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('status', $case->status) == $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hearing Date & Time</label>
                    <input type="datetime-local" name="hearing_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($case->hearing_date)->format('Y-m-d\TH:i') }}">
                    @error('hearing_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Judge Name</label>
                    <input type="text" name="judge_name" class="form-control"
                        value="{{ old('judge_name', $case->judge_name) }}">
                    @error('judge_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @if (Auth::user()->role == 'admin')
                    <div class="col-md-3">
                        <label class="form-label">Total Amount</label>
                        <input type="text" name="amount" class="form-control"
                            value="{{ old('amount', $case->amount) }}" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    @php
                        // If there's no old input and $assignedUserIds is not set, select all user IDs
$selectedUsers = old('assigned_to', $assignedUserIds ?? $users->pluck('id')->toArray());
                    @endphp

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Assigned To</label>
                        <select id="assigned_to_select" name="assigned_to[]" multiple class="form-control">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif


            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea id="summernote" name="description" class="form-control">{{ old('description', $case->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex">
                <button class="btn btn-success" type="submit">Update Case</button>
                <a href="{{ route('cases.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>


    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#assigned_to_select').select2({
                    placeholder: "Select team members",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
