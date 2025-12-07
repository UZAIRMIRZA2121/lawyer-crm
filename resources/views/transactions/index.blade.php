@extends('layouts.app')

@section('content')
    <style>
        /* ===========================
                           PRINT SETTINGS
                           =========================== */
        @media print {

            /* Hide everything */
            body * {
                visibility: hidden !important;
            }

            /* Show only print area */
            .print-area,
            .print-area * {
                visibility: visible !important;
            }

            /* Place at top left */
            .print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Hide Actions column (last column) */
            table tr th:last-child,
            table tr td:last-child {
                display: none !important;
            }

            /* Hide Print button */
            .print-btn {
                display: none !important;
            }
        }
    </style>

    <div class="container">
        <h2>Transactions for Case: {{ $case->case_number ?? '' }}</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">

            <!-- Left Side -->
            <div>
                <a href="{{ route('cases.transactions.create', $case) }}" class="btn btn-primary">
                    Add Transaction
                </a>
            </div>

            <!-- Right Side -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAmountModal">
                    Edit Case Amounts
                </button>

                <button class="btn btn-primary print-btn" onclick="window.print()">
                    Print
                </button>
            </div>

        </div>



        <div class="row">
            <!-- Total Amount -->
            <!-- Total Amount Card -->
            <div class="col-md-4">
                <div class="card border-primary mb-3" data-bs-toggle="modal" data-bs-target="#editAmountModal"
                    style="cursor:pointer;">
                    <div class="card-body text-primary text-center">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs {{ number_format($case->amount, 0) }}</h3>
                        <p class="card-text">Total Amount</p>
                    </div>
                </div>
            </div>
            <!-- Paid Amount -->
            <div class="col-md-4">
                <div class="card border-success mb-3">
                    <div class="card-body text-success text-center">
                        <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs {{ number_format($paidAmount, 0) }}</h3>
                        <p class="card-text">Paid Amount</p>
                    </div>
                </div>
            </div>

            <!-- Remaining Amount -->
            <div class="col-md-4">
                <div class="card border-warning mb-3">
                    <div class="card-body text-warning text-center">
                        <i class="bi bi-wallet-fill" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs {{ number_format($case->amount - $paidAmount, 0) }}</h3>
                        <p class="card-text">Remaining Amount</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Amount -->


            <!-- Total Commission Amount Card -->
            <div class="col-md-4">
                <div class="card border-primary mb-3" data-bs-toggle="modal" data-bs-target="#editCommissionModal"
                    style="cursor:pointer;">
                    <div class="card-body text-primary text-center">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs {{ number_format($case->commission_amount, 0) }}</h3>
                        <p class="card-text">Total Commission Amount</p>
                    </div>
                </div>
            </div>


            <!-- Paid Amount -->
            <div class="col-md-4">
                <div class="card border-success mb-3">
                    <div class="card-body text-success text-center">
                        <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs {{ number_format($commissionPaidAmount, 0) }}</h3>
                        <p class="card-text">Paid Commission Amount</p>
                    </div>
                </div>
            </div>

            <!-- Remaining Amount -->
            <div class="col-md-4">
                <div class="card border-warning mb-3">
                    <div class="card-body text-warning text-center">
                        <i class="bi bi-wallet-fill" style="font-size: 2rem;"></i>
                        <h3 class="card-title mt-2">Rs
                            {{ number_format($case->commission_amount - $commissionPaidAmount, 0) }}</h3>
                        <p class="card-text">Remaining Commission Amount</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="print-area">
            @if ($transactions->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th> <!-- ✅ Added -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    @if ($transaction->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-success">Commission Amount</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('cases.transactions.edit', [$case, $transaction]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('cases.transactions.destroy', [$case, $transaction]) }}"
                                        method="POST" style="display:inline-block;"
                                        onsubmit="return confirm('Delete this transaction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $transactions->links() }}
            @else
                <p>No transactions found.</p>
            @endif

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="editAmountModal" tabindex="-1" aria-labelledby="editAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('cases.updateAmounts', $case->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAmountModalLabel">Edit Amounts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Case Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                value="{{ $case->amount }}" required>
                        </div>
                        <!-- Commission Amount -->
                        <div class="mb-3">
                            <label for="commission_amount" class="form-label">Commission Amount</label>
                            <input type="number" class="form-control" id="commission_amount" name="commission_amount"
                                value="{{ $case->commission_amount }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
