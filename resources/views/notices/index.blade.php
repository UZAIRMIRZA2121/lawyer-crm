@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Notices</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('notices.create') }}" class="btn btn-primary mb-3">Add Notice</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Case</th>
                    <th>User</th>
                    <th>Against Client ID</th>
                    <th>Notice</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notices as $notice)
                    <tr>
                        <td>{{ $notice->case->case_title ?? 'N/A' }}</td>
                        <td>{{ $notice->user->name ?? 'N/A' }}</td>
                        <td>{{ $notice->against_client->name ?? 'N/A' }}</td>
                        <td>{{ $notice->notice }}</td>
                        <td>{{ $notice->status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('notices.edit', $notice) }}" class="btn btn-sm btn-warning">Edit</a>

                            <!-- View button triggers modal -->
                            <button type="button" class="btn btn-sm btn-info btn-view-notice" data-bs-toggle="modal"
                                data-bs-target="#noticeModal" data-notice-base64="{{ $notice->notice_base64 }}">
                                View
                            </button>

                            <form action="{{ route('notices.destroy', $notice) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this notice?')">Delete</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No notices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            <!-- Modal -->
    <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeModalLabel">Notice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="noticeModalBody">
                    <!-- Decoded HTML content will be injected here -->
                    <div class="text-center text-muted">Loading...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script>
        function b64_to_utf8(str) {
            return decodeURIComponent(escape(window.atob(str)));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.getElementById('noticeModalBody');

            const noticeModal = document.getElementById('noticeModal');
            noticeModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const base64Content = button.getAttribute('data-notice-base64');

                if (base64Content) {
                    try {
                        const decodedHtml = b64_to_utf8(base64Content);
                        modalBody.innerHTML = decodedHtml;
                    } catch (e) {
                        modalBody.innerHTML = '<p class="text-danger">Failed to load notice content.</p>';
                    }
                } else {
                    modalBody.innerHTML = '<p class="text-muted">No notice content available.</p>';
                }
            });

            noticeModal.addEventListener('hidden.bs.modal', function() {
                modalBody.innerHTML = '<div class="text-center text-muted">Loading...</div>';
            });
        });
    </script>
@endsection
