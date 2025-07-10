@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Hearing for Case: {{ $case->case_number }}</h2>

    <form action="{{ route('hearings.update', [$case, $hearing]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="judge_name" class="form-label">Judge Name <span class="text-danger">*</span></label>
                <input type="text"
                       name="judge_name"
                       id="judge_name"
                       class="form-control @error('judge_name') is-invalid @enderror"
                       value="{{ old('judge_name', $hearing->judge_name) }}"
                       required>
                @error('judge_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                <select name="priority"
                        id="priority"
                        class="form-select @error('priority') is-invalid @enderror"
                        required>
                    <option value="normal" {{ (old('priority', $hearing->priority) === 'normal') ? 'selected' : '' }}>Normal</option>
                    <option value="important" {{ (old('priority', $hearing->priority) === 'important') ? 'selected' : '' }}>Important</option>
                </select>
                @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="next_hearing" class="form-label">Next Hearing Date & Time</label>
                <input type="datetime-local"
                       name="next_hearing"
                       id="next_hearing"
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
            <label for="judge_remarks" class="form-label">Judge Remarks</label>
            <textarea name="judge_remarks"
                      id="judge_remarks"
                      rows="3"
                      class="form-control @error('judge_remarks') is-invalid @enderror">{{ old('judge_remarks', $hearing->judge_remarks) }}</textarea>
            @error('judge_remarks')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="my_remarks" class="form-label">My Remarks</label>
            <textarea name="my_remarks"
                      id="my_remarks"
                      rows="3"
                      class="form-control @error('my_remarks') is-invalid @enderror">{{ old('my_remarks', $hearing->my_remarks) }}</textarea>
            @error('my_remarks')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Hearing</button>
        <a href="{{ route('hearings.index', $case) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
