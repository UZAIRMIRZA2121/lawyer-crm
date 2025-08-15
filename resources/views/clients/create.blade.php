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

                <div class="mb-3 col-md-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
                        class="form-control">
                </div>
                <div class="mb-3 col-md-3">
                    <label class="form-label" for="referral_by">Referral By</label>
                    <input type="text" name="referral_by" id="referral_by" class="form-control"
                        value="{{ old('referral_by', $client->referral_by ?? '') }}">
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
                        <small class="d-block mt-1">Current:
                            <a href="{{ asset('storage/' . $client->cnic_front) }}" target="_blank">View Front</a>
                        </small>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">CNIC Back Image</label>
                    <input type="file" name="cnic_back" class="form-control">
                    @if (!empty($client->cnic_back))
                        <small class="d-block mt-1">Current:
                            <a href="{{ asset('storage/' . $client->cnic_back) }}" target="_blank">View Back</a>
                        </small>
                    @endif
                </div>
            </div>

            {{-- Multiple Files --}}
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label class="form-label">Additional Files (Multiple)</label>
                    <input type="file" name="upload_files[]" class="form-control" multiple>


                </div>
            </div>

            {{-- Description --}}
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label class="form-label">Description</label>
                    <textarea id="summernote" name="description">{{ old('description', $client->description ?? '') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection

{{-- Push Styles --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

{{-- Push Scripts --}}
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2
            $('.select2').select2({
                placeholder: 'Select a user',
                allowClear: true,
                width: '100%'
            });

            // Summernote
            $('#summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontsize', 'color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });
    </script>

@endpush
