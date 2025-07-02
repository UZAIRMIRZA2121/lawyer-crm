@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Case Details</h1>

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
@endsection
