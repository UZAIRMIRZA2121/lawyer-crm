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

                {{-- ✅ Description --}}
                @if (!empty($client->description))
                    <div class="row mb-4">
                        <div class="col-md-3 fw-semibold text-muted">Description:</div>
                        <div class="col-md-9">{!! $client->description !!}</div>
                    </div>
                @endif
                @if (!empty($client->files) && is_array($client->files))
                    <div class="row mb-4">
                        <div class="col-md-3 fw-semibold text-muted">Additional Files:</div>
                        <div class="col-md-9">
                            <div class="row g-3">
                                @foreach ($client->files as $index => $file)
                                    @php
                                        $fileUrl = asset('public/storage/' . $file);
                                        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    @endphp

                                    <div class="col-md-4 text-center file-wrapper" data-file="{{ $file }}">
                                        <strong class="d-block mb-1">{{ basename($file) }}</strong>

                                        {{-- Buttons --}}
                                        <div class="mb-1">
                                            {{-- Download --}}
                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-success"
                                                title="Download">
                                                <i class="bi bi-download"></i> {{-- Bootstrap icon --}}
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button" class="btn btn-sm btn-danger delete-file-btn"
                                                data-file="{{ $file }}" title="Delete">
                                                <i class="bi bi-trash"></i> {{-- Bootstrap icon --}}
                                            </button>
                                        </div>

                                        {{-- Preview --}}
                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        
                                            <a href="{{ $fileUrl }}" target="_blank"
                                                class="d-inline-block border rounded shadow-sm overflow-hidden"
                                                style="max-width: 320px;">
                                                <img src="{{ $fileUrl }}" alt="File Preview" class="img-fluid"
                                                    style="max-height: 200px; object-fit: contain;">
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank"
                                                class="d-inline-block border rounded shadow-sm p-2">
                                                📄 Open File
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif


                {{-- ✅ CNIC Images --}}
                <div class="row mb-4">
                    @if ($client->cnic_front)
                        <div class="col-md-6 mb-3 text-center">
                            <div class="fw-semibold mb-2 text-muted">CNIC Front:</div>
                            <a href="{{ asset('storage/' . $client->cnic_front) }}" target="_blank"
                                class="d-inline-block border rounded shadow-sm overflow-hidden" style="max-width: 320px;">
                                <img src="{{ Storage::url($client->cnic_front) }}" alt="CNIC Front" class="img-fluid"
                                    style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    @endif

                    @if ($client->cnic_back)
                        <div class="col-md-6 mb-3 text-center">
                            <div class="fw-semibold mb-2 text-muted">CNIC Back:</div>
                            <a href="{{ asset('storage/' . $client->cnic_back) }}" target="_blank"
                                class="d-inline-block border rounded shadow-sm overflow-hidden" style="max-width: 320px;">
                                <img src="{{ Storage::url($client->cnic_back) }}" alt="CNIC Back" class="img-fluid"
                                    style="max-height: 200px; object-fit: contain;">
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

@endsection
