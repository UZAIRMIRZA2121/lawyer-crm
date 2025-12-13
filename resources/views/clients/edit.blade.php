@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Client</h1>

        <form action="{{ route('clients.update', $client) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                        <small class="d-block mt-1">Current:</small>
                        <a href="{{ asset('public/storage/' . $client->cnic_front) }}" target="_blank" class="d-inline-block mb-2">
                            <img src="{{ asset('storage/' . $client->cnic_front) }}" alt="CNIC Front"
                                style="max-width: 150px; max-height: 100px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px;">
                        </a>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">CNIC Back Image</label>
                    <input type="file" name="cnic_back" class="form-control">

                    @if (!empty($client->cnic_back))
                        <small class="d-block mt-1">Current:</small>
                        <a href="{{ asset('public/storage/' . $client->cnic_back) }}" target="_blank" class="d-inline-block mb-2">
                            <img src="{{ asset('storage/' . $client->cnic_back) }}" alt="CNIC Back"
                                style="max-width: 150px; max-height: 100px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px;">
                        </a>
                    @endif
                </div>
                {{-- <div class="mb-3 col-md-6">
                    <label class="form-label">Assigned To</label>
                    <select id="assigned_to_select" name="assigned_to[]" multiple class="form-control">
                        @foreach ($users as $user)
                            @if ($user->role === 'team')
                                <option value="{{ $user->id }}"
                                    {{ isset($client) && $client->assignedUsers->contains($user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div> --}}

            </div>
            {{-- Multiple Files --}}
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Additional Files (Multiple)</label>
                    <input type="file" name="upload_files[]" class="form-control" multiple>
                </div>
                {{-- ✅ Additional Files --}}
                @if (!empty($client->files) && is_array($client->files))
                    <div class="col-md-12">
                        <div class="row g-3">
                            @foreach ($client->files as $index => $file)
                                @php
                                    $fileUrl = asset('public/storage/' . $file);
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp


                                <div class="col-md-2 text-center file-wrapper" data-file="{{ $file }}">
                                    <div class="position-relative border rounded shadow-sm p-2 mx-auto"
                                        style="width: 120px; height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden;">

                                        {{-- Delete Button --}}
                                        <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-file-btn"
                                            data-file="{{ $file }}">
                                            &times;
                                        </button>
                                        {{-- File Preview --}}
                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ $fileUrl }}" target="_blank">
                                                <img src="{{ $fileUrl }}" alt="File Preview"
                                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank"
                                                style="display: flex; align-items: center; justify-content: center; width: 100px; height: 100px; background: #f1f1f1; border-radius: 4px; font-size: 14px;">
                                                📄 File
                                            </a>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-truncate" style="max-width: 120px; font-size: 12px;">
                                        {{ basename($file) }}</p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif

            </div>

            {{-- Description --}}
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label class="form-label">Description</label>
                    <textarea id="summernote" name="description">{{ old('description', $client->description ?? '') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>

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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".delete-file-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        let filePath = this.getAttribute("data-file");
                        let wrapper = this.closest(".file-wrapper");

                        if (confirm("Are you sure you want to delete this file?")) {
                            fetch("{{ route('clients.deleteFile', $client->id) }}", {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        file_path: filePath
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        wrapper.remove(); // Remove from DOM
                                    } else {
                                        alert(data.message || "Failed to delete file.");
                                    }
                                })
                                .catch(() => alert("Something went wrong!"));
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
