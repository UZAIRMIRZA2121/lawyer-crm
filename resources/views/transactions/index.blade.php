@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Transactions for Case: {{ $case->case_number }}</h2>

        <a href="{{ route('cases.transactions.create', $case) }}" class="btn btn-primary mb-3">Add Transaction</a>

        <div class="row">
            <!-- Total Amount -->
            <div class="col-md-4">
                <div class="card border-primary mb-3">
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
                        <h3 class="card-title mt-2">Rs {{ number_format($remainingAmount, 0) }}</h3>
                        <p class="card-text">Remaining Amount</p>
                    </div>
                </div>
            </div>
        </div>


        @if ($transactions->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Description</th>
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
@endsection
