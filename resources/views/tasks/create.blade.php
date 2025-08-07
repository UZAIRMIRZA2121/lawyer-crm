@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">


    <div class="container">
        <h2>{{ isset($task) ? 'Edit Task' : 'Create Task' }}</h2>

        <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
            @csrf
            @if (isset($task))
                @method('PUT')
            @endif

            <div class="row">
                @php
                    $selectedUsers = old('user_ids', isset($task) ? [$task->user_id] : []);
                @endphp

                <div class="col-md-3 mb-3">
                    <label for="user_id" class="form-label">Users</label>
                    <select name="user_ids[]" class="form-select select2" multiple required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedUsers) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_ids')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select name="priority" class="form-select" required>
                        <option value="normal" {{ old('priority', $task->priority ?? '') == 'normal' ? 'selected' : '' }}>
                            Normal
                        </option>
                        <option value="urgent" {{ old('priority', $task->priority ?? '') == 'urgent' ? 'selected' : '' }}>
                            Urgent
                        </option>
                        <option value="important"
                            {{ old('priority', $task->priority ?? '') == 'important' ? 'selected' : '' }}>
                            Important
                        </option>
                    </select>
                    @error('priority')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="submit_date" class="form-label">Submit Date</label>
                    <input type="date" name="submit_date" class="form-control"
                        value="{{ old('submit_date', $task->submit_date ?? '') }}" required>
                    @error('submit_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ old('status', $task->status ?? '') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="done" {{ old('status', $task->status ?? '') == 'done' ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="task" class="form-label">Task Description</label>
                    <textarea name="task" id="summernote" class="form-control" rows="4" required>{{ old('task', $task->task ?? '') }}</textarea>
                    @error('task')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select a user',
                allowClear: true
            });

            $('#summernote').summernote({
                height: 200
            });
        });
    </script>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select users",
                    allowClear: true
                });
            });
        </script>
    @endpush
@endsection
