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
            <table class="table table-bordered table-striped align-middle mb-5">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Case #</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Remaining</th>
                        <th>Commission</th>
                        <th>Commission Paid</th>
                        <th>Remaining Commission</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientCases as $case)
                        @php
                            $paid = $case->transactions->where('status', 'paid')->sum('amount');
                            $commissionPaid = $case->transactions->where('status', 'commission')->sum('amount');

                            if ($paid == 0) {
                                $status = 'Unpaid';
                                $badge = 'bg-danger';
                            } elseif ($paid >= $case->amount) {
                                $status = 'Paid';
                                $badge = 'bg-success';
                            } else {
                                $status = 'Partial Paid';
                                $badge = 'bg-warning';
                            }
                        @endphp

                        <tr class="text-center">
                            <td>
                                <a href="{{ route('clients.show', $case->client_id) }}" class="text-decoration-none">
                                    {{ $case->case_number ?? '' }}
                                </a>
                            </td>
                            <td>Rs {{ number_format($case->amount, 0) }}</td>
                            <td>Rs {{ number_format($paid, 0) }}</td>
                            <td>Rs {{ number_format($case->amount - $paid, 0) }}</td>
                            <td>Rs {{ number_format($case->commission_amount, 0) }}</td>
                            <td>Rs {{ number_format($commissionPaid, 0) }}</td>
                            <td>Rs {{ number_format($case->commission_amount - $commissionPaid, 0) }}</td>
                            <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
                            <td>
                                @if ($status != 'Paid')
                                    <a href="{{ route('cases.transactions.index', $case->id) }}"
                                        class="btn btn-sm btn-primary">
                                        Payment
                                    </a>
                                @endif
                            </td>
                        </tr>

                
                    @endforeach
                </tbody>
            </table>
        @endforeach

    </div>
@endsection
