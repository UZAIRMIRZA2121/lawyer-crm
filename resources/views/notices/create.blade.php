@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Add Notice & عدالتی حاضری فارم</h1>

        <form action="{{ route('notices.store') }}" method="POST" target="_blank">
            @csrf

            {{-- === Notice Section === --}}
            <h3 class="mb-3">Add Notice</h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Case</label>
                    <select name="case_id" id="case_id" class="form-select">
                        <option value="">-- Select Case --</option>
                        @foreach ($cases as $case)
                            <option value="{{ $case->id }}">{{ $case->case_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Against Client</label>
                    <select name="against_client_id" id="against_client_id" class="form-select">
                        <option value="">-- Select Client --</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notice</label>
                <textarea name="notice" id="notice" class="form-control" required>{{ old('notice') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <hr>

            {{-- === عدالتی حاضری فارم Section === --}}
            <h3 class="mb-3 text-center">عدالتی حاضری فارم</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">جج کا نام</label>
                        <input type="text" name="judge_name" class="form-control text-end"
                            placeholder="مثلاً: سینئر سول جج، فیصل آباد" value="{{ old('judge_name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">مقدمہ نمبر / عدالت</label>
                        <input type="text" name="case_number" class="form-control text-end"
                            value="{{ old('case_number') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">مدعی کا نام</label>
                        <input type="text" name="plaintiff_name" class="form-control text-end"
                            value="{{ old('plaintiff_name') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">مدعی کا پتہ</label>
                        <input type="text" name="plaintiff_address" class="form-control text-end"
                            value="{{ old('plaintiff_address') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">نام مدعا علیہ</label>
                        <input type="text" name="defendant_name" class="form-control text-end"
                            value="{{ old('defendant_name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">ولدیت / پتہ مدعا علیہ</label>
                        <input type="text" name="defendant_father_address" class="form-control text-end"
                            value="{{ old('defendant_father_address') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">مدعا علیہ کی حیثیت</label>
                        <input type="text" name="defendant_role" class="form-control text-end"
                            placeholder="مثلاً: گواہ، مخالف" value="{{ old('defendant_role') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">تاریخ پیشی</label>
                        <input type="date" name="hearing_date" class="form-control text-end"
                            value="{{ old('hearing_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">وقت</label>
                        <input type="time" name="hearing_time" class="form-control text-end"
                            value="{{ old('hearing_time', '08:00') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">مہینہ / سال</label>
                        <input type="text" name="month_year" class="form-control text-end"
                            placeholder="مثلاً: جولائی 2025" value="{{ old('month_year') }}">
                    </div>
                </div>



            </div>











            <button type="submit" class="btn btn-success w-100">Create & Print</button>
        </form>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Summernote --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#case_id').select2({
                placeholder: '-- Select Case --'
            });
            $('#against_client_id').select2({
                placeholder: '-- Select Client --'
            });

            // Initialize Summernote
            $('#notice').summernote({
                height: 200
            });

            // AJAX Load Clients on Case change
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
                                clientSelect.append(
                                    '<option value="">No clients found</option>');
                            } else if (data.length === 1) {
                                clientSelect.append('<option value="' + data[0].id +
                                    '" selected>' + data[0].name + '</option>');
                            } else {
                                clientSelect.append(
                                    '<option value="">-- Select Client --</option>');
                                $.each(data, function(index, client) {
                                    clientSelect.append('<option value="' + client.id +
                                        '">' + client.name + '</option>');
                                });
                            }

                            // Refresh Select2
                            clientSelect.trigger('change');
                        },
                        error: function() {
                            clientSelect.empty().append(
                                '<option value="">Error loading clients</option>');
                        }
                    });
                } else {
                    clientSelect.empty().append('<option value="">-- Select Client --</option>');
                }
            });
        });
    </script>
@endsection
