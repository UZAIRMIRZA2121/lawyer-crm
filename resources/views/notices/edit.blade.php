@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Notice</h1>

    <form action="{{ route('notices.update', $notice) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Case</label>
                <select name="case_id" id="case_id" class="form-select">
                    <option value="">-- Select Case --</option>
                    @foreach ($cases as $case)
                        <option value="{{ $case->id }}" {{ $notice->case_id == $case->id ? 'selected' : '' }}>
                            {{ $case->case_title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Against Client</label>
                <select name="against_client_id" id="against_client_id" class="form-select">
                    <option value="">-- Select Client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ $notice->against_client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="mb-3">
            <label class="form-label">Notice</label>
            <textarea name="notice" id="notice" class="form-control" required>{{ old('notice', $notice->notice ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ $notice->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$notice->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Summernote --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

{{-- (Optional) Select2 for dropdowns --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#notice').summernote({
        height: 200
    });

    // (Optional) Initialize Select2
    $('#case_id, #against_client_id').select2({
        placeholder: '-- Select --',
        allowClear: true,
        width: '100%'
    });

    // AJAX load clients when case is changed
    $('#case_id').on('change', function() {
        var caseId = $(this).val();
        var clientSelect = $('#against_client_id');

        clientSelect.empty().append('<option value="">Loading...</option>');

        if (caseId) {
            $.ajax({
                url: '/notices/clients-by-case/' + caseId,
                type: 'GET',
                success: function(data) {
                    clientSelect.empty();
                    if (data.length === 0) {
                        clientSelect.append('<option value="">No clients found</option>');
                    } else if (data.length === 1) {
                        clientSelect.append('<option value="' + data[0].id + '" selected>' + data[0].name + '</option>');
                    } else {
                        clientSelect.append('<option value="">-- Select Client --</option>');
                        $.each(data, function(index, client) {
                            clientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                        });
                    }

                    // Refresh Select2 after updating options
                    clientSelect.trigger('change');
                },
                error: function() {
                    clientSelect.empty().append('<option value="">Error loading clients</option>');
                }
            });
        } else {
            clientSelect.empty().append('<option value="">-- Select Client --</option>');
        }
    });
});
</script>
@endsection
