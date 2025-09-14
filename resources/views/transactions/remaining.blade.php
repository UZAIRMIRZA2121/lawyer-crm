@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>All Cases Grouped by Client</h2>
        <div class="mb-4">
            <form method="GET" action="{{ route('remaining.amount') }}" class="row g-2 align-items-end">
                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by Case # or Client"
                        value="{{ request('search') }}">
                </div>


                <!-- Submit Button -->
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
            <!-- Payment Status Filter -->
            <div class="col-md-6">
                <label class="form-label">Payment Status</label>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('remaining.amount', array_merge(request()->except('page', 'status'), ['status' => null])) }}"
                        class="btn  btn-sm {{ request('status') === null ? 'btn-primary' : '' }}">
                        All
                    </a>
                    @php
                        $statusFilters = [
                            'paid' => 'Paid',
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partial Paid',
                        ];
                    @endphp
                    @foreach ($statusFilters as $key => $label)
                        <a href="{{ route('remaining.amount', array_merge(request()->except('page'), ['status' => $key])) }}"
                            class="btn  btn-sm {{ request('status') === $key ? 'btn-primary' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

        </div>



        @foreach ($cases->groupBy('user_id') as $clientId => $clientCases)
            <div class="mb-5">


                @foreach ($clientCases as $case)
                    @php
                        $paid = $case->transactions->where('status', 'paid')->sum('amount');
                        $status = 'Unpaid';
                        if ($paid == 0) {
                            $status = 'Unpaid';
                        } elseif ($paid >= $case->amount) {
                            $status = 'Paid';
                        } else {
                            $status = 'Partial Paid';
                        }
                    @endphp


                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('clients.show', $case->client_id) }}" class="text-decoration-none">
                                    <strong>Case #{{ $case->case_number ?? '' }}</strong>
                                </a>

                                <span
                                    class="badge 
            {{ $status == 'Paid' ? 'bg-success' : ($status == 'Partial Paid' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $status }}
                                </span>
                            </div>

                            @if ($status != 'Paid')
                                <a href="{{ route('cases.transactions.index', $case->id) }}"
                                    class="btn btn-primary btn-sm">
                                    Payment
                                </a>
                            @endif
                        </div>

                        <div class="card-body">

                            <div class="row text-center mb-3">
                                <!-- Total -->
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <h4>Rs {{ number_format($case->amount, 0) }}</h4>
                                        <small>Total Amount</small>
                                    </div>
                                </div>

                                <!-- Paid -->
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        @php
                                            $paid = $case->transactions->where('status', 'paid')->sum('amount');
                                        @endphp
                                        <h4>Rs {{ number_format($paid, 0) }}</h4>
                                        <small>Paid Amount</small>
                                    </div>
                                </div>

                                <!-- Remaining -->
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <h4>Rs {{ number_format($case->amount - $paid, 0) }}</h4>
                                        <small>Remaining Amount</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission -->
                            <div class="row text-center mb-3">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <h4>Rs {{ number_format($case->commission_amount, 0) }}</h4>
                                        <small>Total Commission</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        @php
                                            $commissionPaid = $case->transactions
                                                ->where('status', 'commission')
                                                ->sum('amount');
                                        @endphp
                                        <h4>Rs {{ number_format($commissionPaid, 0) }}</h4>
                                        <small>Commission Paid</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <h4>Rs {{ number_format($case->commission_amount - $commissionPaid, 0) }}</h4>
                                        <small>Remaining Commission</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions Table -->
                            @if ($case->transactions->count())
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Payment Method</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($case->transactions as $transaction)
                                            <tr>
                                                <td>{{ number_format($transaction->amount, 2) }}</td>
                                                <td>{{ ucfirst($transaction->type) }}</td>
                                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y H:i') }}
                                                </td>
                                                <td>{{ $transaction->description ?? '-' }}</td>
                                                <td>
                                                    @if ($transaction->status === 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-info">Commission</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No transactions found for this case.</p>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
