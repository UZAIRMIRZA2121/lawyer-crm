@extends('layouts.app')

@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .table-responsive,
            .table-responsive * {
                visibility: visible;
            }

            .table-responsive {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            table {
                font-size: 16px;
                font-weight: 600;
            }

            table,
            th,
            td {
                border: 1px solid #000 !important;
            }

            /* Remove badge styling but keep text */
            .badge {
                background: none !important;
                color: #000 !important;
                padding: 0 !important;
                border-radius: 0 !important;
                font-size: 14px !important;
                font-weight: normal !important;
            }
        }
    </style>
    <div class="container">
        <h1 class="mb-4">Notices</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Print Button -->
        <div class="col-md-4">
            <a href="{{ route('notices.create') }}" class="btn btn-primary mb-3">Add Notice</a>

            <button type="button" class="btn btn-primary mb-3 " onclick="printTable()">🖨️ Print
                Table</button>
        </div>
        <!-- Filters -->
        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label">Priority</label>
                <div class="d-flex flex-wrap gap-1">
                    @php
                        $priorities = ['normal' => 'Normal', 'urgent' => 'Urgent', 'important' => 'Important'];
                    @endphp
                    <a href="{{ route('notices.index', array_merge(request()->except(['page', 'priority']), ['priority' => null])) }}"
                        class="btn btn-sm {{ request('priority') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @foreach ($priorities as $key => $label)
                        <a href="{{ route('notices.index', array_merge(request()->except('page'), ['priority' => $key])) }}"
                            class="btn btn-sm {{ request('priority') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <div class="d-flex flex-wrap gap-1">
                    @php
                        $statuses = ['pending' => 'Pending', 'done' => 'Done'];
                    @endphp
                    <a href="{{ route('notices.index', array_merge(request()->except(['page', 'status']), ['status' => null])) }}"
                        class="btn btn-sm {{ request('status') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @foreach ($statuses as $key => $label)
                        <a href="{{ route('notices.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>


        </div>

        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-striped align-middle table-fixed-header">
                <thead class="table-light">
                    <thead>
                        <tr>
                            <th>Case</th>
                            <th>User</th>
                            <th>Against Client</th>
                            <th>Priority</th>
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
                            <td>
                                @php
                                    $priorityColors = [
                                        'urgent' => 'danger',
                                        'important' => 'warning',
                                        'normal' => 'success',
                                    ];
                                    $priority = strtolower($notice->priority ?? 'normal');
                                    $priorityClass = $priorityColors[$priority] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $priorityClass }}">{{ ucfirst($priority) }}</span>
                            </td>

                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'done' => 'success',
                                    ];
                                    $status = strtolower($notice->status);
                                    $statusClass = $statusColors[$status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span>
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-info btn-view-notice" data-bs-toggle="modal"
                                    data-bs-target="#noticeModal" data-notice-base64="{{ $notice->notice_base64 }}">
                                    View
                                </button>

                                <form action="{{ route('notices.destroy', $notice) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
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
        </div>
        <!-- Modal -->
        <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noticeModalLabel">Notice Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="noticeModalBody">
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


    <script>
        function printTable() {
            // Hide the Actions column before printing
            const actionColIndexes = [];
            const ths = document.querySelectorAll('table thead th');
            ths.forEach((th, index) => {
                if (th.innerText.trim().toLowerCase() === 'actions') {
                    actionColIndexes.push(index);
                }
            });

            // Hide Action column cells
            const rows = document.querySelectorAll('table tr');
            rows.forEach(row => {
                actionColIndexes.forEach(i => {
                    if (row.children[i]) {
                        row.children[i].style.display = 'none';
                    }
                });
            });

            // Trigger print
            window.print();

            // Restore Action column after printing
            setTimeout(() => {
                rows.forEach(row => {
                    actionColIndexes.forEach(i => {
                        if (row.children[i]) {
                            row.children[i].style.display = '';
                        }
                    });
                });
            }, 1000);
        }
    </script>
@endsection
