@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Add New Client</h1>

        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}" class="form-control"
                        required>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" value="{{ old('cnic', $client->cnic ?? '') }}"
                        class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Contact No.</label>
                    <input type="text" name="contact_no" value="{{ old('contact_no', $client->contact_no ?? '') }}"
                        class="form-control">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
                        class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control">{{ old('address', $client->address ?? '') }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">CNIC Front Image</label>
                    <input type="file" name="cnic_front" class="form-control">
                    @if (!empty($client->cnic_front))
                        <small class="d-block mt-1">Current: <a href="{{ asset('storage/' . $client->cnic_front) }}"
                                target="_blank">View Front</a></small>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">CNIC Back Image</label>
                    <input type="file" name="cnic_back" class="form-control">
                    @if (!empty($client->cnic_back))
                        <small class="d-block mt-1">Current: <a href="{{ asset('storage/' . $client->cnic_back) }}"
                                target="_blank">View Back</a></small>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
