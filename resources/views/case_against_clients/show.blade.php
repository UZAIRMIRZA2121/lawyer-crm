@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">Client Details</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">Name:</div>
                <div class="col-md-9">{{ $client->name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">CNIC:</div>
                <div class="col-md-9">{{ $client->cnic }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">Contact No.:</div>
                <div class="col-md-9">{{ $client->contact_no }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-muted">Email:</div>
                <div class="col-md-9">{{ $client->email }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 fw-semibold text-muted">Address:</div>
                <div class="col-md-9">{{ $client->address }}</div>
            </div>

            <div class="row mb-4">
                @if ($client->cnic_front)
                    <div class="col-md-6 mb-3 text-center">
                        <div class="fw-semibold mb-2 text-muted">CNIC Front:</div>
                        <a href="{{ asset('storage/' . $client->cnic_front) }}" target="_blank" class="d-inline-block border rounded shadow-sm overflow-hidden" style="max-width: 320px;">
                            <img src="{{ Storage::url($client->cnic_front) }}" alt="CNIC Front" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                        </a>
                    </div>
                @endif

                @if ($client->cnic_back)
                    <div class="col-md-6 mb-3 text-center">
                        <div class="fw-semibold mb-2 text-muted">CNIC Back:</div>
                        <a href="{{ asset('storage/' . $client->cnic_back) }}" target="_blank" class="d-inline-block border rounded shadow-sm overflow-hidden" style="max-width: 320px;">
                            <img src="{{ asset('public/storage/' . $client->cnic_back) }}" alt="CNIC Back" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                        </a>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
