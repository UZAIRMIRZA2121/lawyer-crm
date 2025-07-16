@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($caseAgainstClient) ? 'Edit' : 'Add' }} Client Against Case</h1>

    <form
        action="{{ isset($caseAgainstClient) ? route('case-against-clients.update', $caseAgainstClient) : route('case-against-clients.store') }}"
        method="POST">
        @csrf
        @if (isset($caseAgainstClient))
            @method('PUT')
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="case_id" class="form-label">Case</label>
                <select name="case_id" id="case_id" class="form-control">
                    @foreach ($cases as $case)
                        <option value="{{ $case->id }}"
                            {{ (isset($caseAgainstClient) && $caseAgainstClient->case_id == $case->id) ? 'selected' : '' }}>
                            {{ $case->case_title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $caseAgainstClient->name ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">CNIC</label>
                <input type="text" name="cnic" class="form-control"
                    value="{{ old('cnic', $caseAgainstClient->cnic ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                    value="{{ old('address', $caseAgainstClient->address ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $caseAgainstClient->phone ?? '') }}">
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success">{{ isset($caseAgainstClient) ? 'Update' : 'Create' }}</button>
        </div>
    </form>
</div>
@endsection
