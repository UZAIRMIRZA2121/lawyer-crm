@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Transaction for Case: {{ $case->case_number }}</h2>

        <form action="{{ route('cases.transactions.update', [$case, $transaction]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control"
                        value="{{ old('amount', $transaction->amount) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="credit" {{ $transaction->type == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ $transaction->type == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank" {{ $transaction->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="online" {{ $transaction->payment_method == 'online' ? 'selected' : '' }}>Online
                        </option>
                        <option value="other" {{ $transaction->payment_method == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Transaction Date <span class="text-danger">*</span></label>
                <input type="datetime-local" name="transaction_date" class="form-control"
                    value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d\TH:i')) }}"
                    required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $transaction->description) }}</textarea>
            </div>
            <div class="row">
                <!-- existing fields -->

                <div class="col-md-4 mb-3">
                    <label>Status <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_paid" value="paid"
                            {{ old('status', $transaction->status) == 'paid' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_paid">
                            Paid
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_pending" value="pending"
                            {{ old('status', $transaction->status) == 'pending' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_pending">
                            Pending
                        </label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary">Update Transaction</button>
            <a href="{{ route('cases.transactions.index', $case) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
