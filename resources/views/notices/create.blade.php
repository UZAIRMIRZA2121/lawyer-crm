@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Add Notice & عدالتی حاضری فارم</h1>

        <form action="{{ route('notices.store') }}" method="POST" target="_blank">
            @csrf

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

                <div class="mb-3 col-md-6">
                    <label class="form-label d-block">Status</label>
                    @php
                        $statuses = [
                            'pending' => 'Pending',
                            'done' => 'Done',
                        ];
                    @endphp
                    @foreach ($statuses as $key => $label)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_{{ $key }}"
                                value="{{ $key }}" {{ old('status') == $key ? 'checked' : '' }} required>
                            <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label d-block">Priority</label>
                    @php
                        $priorities = [
                            'normal' => 'Normal',
                            'urgent' => 'Urgent',
                            'important' => 'Important',
                        ];
                    @endphp
                    @foreach ($priorities as $key => $label)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="priority" id="priority_{{ $key }}"
                                value="{{ $key }}" {{ old('priority') == $key ? 'checked' : '' }} required>
                            <label class="form-check-label" for="priority_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @error('priority')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


            </div>

            <div class="mb-3">
                <label class="form-label">Notice</label>
                <textarea name="notice" id="notice" class="form-control summernote">{{ old('notice') }}</textarea>
            </div>


            <hr>
            <h3 class="mb-3 text-center">عدالتی حاضری فارم</h3>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">جج کا نام</label>
                    <input type="text" name="judge_name" class="form-control text-end" value="{{ old('judge_name') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">مقدمہ نمبر / عدالت</label>
                    <input type="text" name="case_number" class="form-control text-end"
                        value="{{ old('case_number') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">مدعی کا نام</label>
                    <input type="text" name="plaintiff_name" class="form-control text-end"
                        value="{{ old('plaintiff_name') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">مدعی کا پتہ</label>
                    <input type="text" name="plaintiff_address" class="form-control text-end"
                        value="{{ old('plaintiff_address') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">نام مدعا علیہ</label>
                    <input type="text" name="defendant_name" class="form-control text-end"
                        value="{{ old('defendant_name') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">ولدیت / پتہ مدعا علیہ</label>
                    <input type="text" name="defendant_father_address" class="form-control text-end"
                        value="{{ old('defendant_father_address') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">مدعا علیہ کی حیثیت</label>
                    <input type="text" name="defendant_role" class="form-control text-end"
                        value="{{ old('defendant_role') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">تاریخ پیشی</label>
                    <input type="date" name="hearing_date" class="form-control text-end"
                        value="{{ old('hearing_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">وقت</label>
                    <input type="time" name="hearing_time" class="form-control text-end"
                        value="{{ old('hearing_time', '08:00') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">مہینہ / سال</label>
                    <input type="text" name="month_year" class="form-control text-end"
                        placeholder="مثلاً: جولائی 2025" value="{{ old('month_year') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">Create & Print</button>
        </form>
    </div>
@endsection


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#case_id, #against_client_id').select2();
            $('.summernote').summernote();

            let loadedAgainstClients = [];

            $('#case_id').on('change', function() {
                const caseId = $(this).val();

                if (!caseId) return;

                $.ajax({
                    url: `/notices/clients-by-case/${caseId}`,
                    method: 'GET',
                    success: function(data) {
                        const caseData = data.case;
                        const mainClient = caseData.client;
                        loadedAgainstClients = data.against_clients;

                        // Prefill form
                        $('input[name="case_number"]').val(caseData.case_number ?? '');
                        $('input[name="judge_name"]').val(caseData.judge_name ?? '');
                        $('input[name="hearing_date"]').val(caseData.hearing_date?.split(' ')[
                            0] ?? '');
                        $('input[name="hearing_time"]').val(caseData.hearing_date?.split(' ')[1]
                            ?.slice(0, 5) ?? '');
                        $('input[name="month_year"]').val(formatMonthYear(caseData
                            .hearing_date));
                        $('input[name="plaintiff_name"]').val(mainClient?.name ?? '');
                        $('input[name="plaintiff_address"]').val(mainClient?.address ?? '');

                        const $clientSelect = $('#against_client_id');
                        $clientSelect.empty().append(
                            '<option value="">-- Select Client --</option>');
                        loadedAgainstClients.forEach(client => {
                            $clientSelect.append(
                                `<option value="${client.id}">${client.name}</option>`
                            );
                        });

                        $clientSelect.trigger('change');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#against_client_id').on('change', function() {
                const selectedId = $(this).val();
                const selectedClient = loadedAgainstClients.find(c => c.id == selectedId);

                $('input[name="defendant_name"]').val(selectedClient?.name ?? '');
                $('input[name="defendant_father_address"]').val(selectedClient?.address ?? '');
                $('input[name="defendant_role"]').val(selectedClient ? 'مدعا علیہ' : '');
            });

            function formatMonthYear(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleString('ur-PK', {
                    month: 'long',
                    year: 'numeric'
                });
            }
        });
    </script>
@endpush
