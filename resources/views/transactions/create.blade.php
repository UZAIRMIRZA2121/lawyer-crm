@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add Transaction for Case: {{ $case->case_number }}</h2>

        <form action="{{ route('cases.transactions.store', $case) }}" method="POST">
            @csrf
          
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}"
                        required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="online">Online</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Transaction Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="transaction_date" class="form-control"
                        value="{{ old('transaction_date') }}">
                </div>
            </div>



            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-3 mb-3">
                <label>Status <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="paid" value="paid"
                        {{ old('status') == 'paid' ? 'checked' : '' }} required checked>
                    <label class="form-check-label" for="paid">
                        Paid
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="commission" value="commission"
                        {{ old('status') == 'commission' ? 'checked' : '' }}>
                    <label class="form-check-label" for="commission">
                        Commission
                    </label>
                </div>
            </div>

            <button class="btn btn-primary">Save Transaction</button>
            <a href="{{ route('cases.transactions.index', $case) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
