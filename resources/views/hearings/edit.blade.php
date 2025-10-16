@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Hearing for Case: {{ $case->case_number }}</h2>

        <form action="{{ route('hearings.update', [$case, $hearing]) }}" method="POST" id="hearingForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="case_id" value="{{ request()->query('case_id') }}" readonly>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="judge_name" class="form-label">Judge Name <span class="text-danger">*</span></label>
                    <input type="text" name="judge_name" id="judge_name"
                        class="form-control @error('judge_name') is-invalid @enderror"
                        value="{{ old('judge_name', $hearing->judge_name) }}" required>
                    @error('judge_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Radio Buttons -->
                <div class="col-md-3">
                    <label class="form-label d-block">Status <span class="text-danger">*</span></label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusPending" value="pending"
                            {{ old('status', $hearing->status ?? '') === 'pending' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="statusPending">Pending</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusDone" value="done"
                            {{ old('status', $hearing->status ?? '') === 'done' ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusDone">Done</label>
                    </div>

                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority Radio Buttons -->
                <div class="col-md-3">
                    <label class="form-label d-block">Priority <span class="text-danger">*</span></label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="priority" id="priorityNormal" value="normal"
                            {{ old('priority', $hearing->priority ?? '') === 'normal' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="priorityNormal">Normal</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="priority" id="priorityImportant"
                            value="important"
                            {{ old('priority', $hearing->priority ?? '') === 'important' ? 'checked' : '' }}>
                        <label class="form-check-label" for="priorityImportant">Important</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="priority" id="priorityUrgent" value="urgent"
                            {{ old('priority', $hearing->priority ?? '') === 'urgent' ? 'checked' : '' }}>
                        <label class="form-check-label" for="priorityUrgent">Urgent</label>
                    </div>

                    @error('priority')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="next_hearing" class="form-label">Next Hearing Date & Time</label>
                    <input type="datetime-local" name="next_hearing" id="next_hearing"
                        class="form-control @error('next_hearing') is-invalid @enderror"
                        value="{{ old('next_hearing', $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('Y-m-d\TH:i') : '') }}">
                    @error('next_hearing')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    {{-- You can add an additional field here if needed --}}
                </div>

                <div class="col-md-6">
                    <label for="judge_remarks" class="form-label">Current Proceeding</label>
                    <textarea name="judge_remarks" id="judge_remarks" rows="3"
                        class="form-control @error('judge_remarks') is-invalid @enderror">{{ old('judge_remarks', $hearing->judge_remarks) }}</textarea>
                    @error('judge_remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="my_remarks" class="form-label">My Remarks</label>
                    <textarea name="my_remarks" id="my_remarks" rows="3"
                        class="form-control @error('my_remarks') is-invalid @enderror">{{ old('my_remarks', $hearing->my_remarks) }}</textarea>
                    @error('my_remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nature" class="form-label">Nature</label>
                    <textarea name="nature" id="nature" rows="3" class="form-control @error('nature') is-invalid @enderror">{{ old('nature') }}</textarea>
                    @error('nature')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Hearing</button>
            <a href="{{ route('hearings.index', $case) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('hearingForm');
            const nextHearingInput = document.getElementById('next_hearing');

            form.addEventListener('submit', function(e) {
                const nextHearingValue = nextHearingInput.value;

                if (nextHearingValue) {
                    const selectedDate = new Date(nextHearingValue);
                    const now = new Date();

                    if (selectedDate <= now) {
                        e.preventDefault(); // Stop form submission
                        alert('Next Hearing Date & Time must be greater than the current date and time.');
                        nextHearingInput.focus();
                    }
                }
            });
        });
    </script> --}}
@endsection
