<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CaseModel;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(CaseModel $case)
    {
        if (auth()->user()->role === 'team') {
            return redirect()->back()->with('error', 'You do not have access to this case.');
        }
        $transactions = $case->transactions()->latest()->paginate(10);


        $totalAmount = $case->transactions()->sum('amount');
        $paidAmount = $case->transactions()->where('status', 'paid')->sum('amount');
        $commissionPaidAmount = $case->transactions()->where('status', 'commission')->sum('amount');

        return view('transactions.index', compact('case', 'transactions', 'totalAmount', 'paidAmount', 'commissionPaidAmount'));
    }



    public function create(CaseModel $case)
    {
        return view('transactions.create', compact('case'));
    }

    public function store(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'nullable|in:credit,debit',
            'payment_method' => 'nullable|in:cash,bank,online,other',
            'transaction_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|in:paid,commission',
        ]);

        $validated['case_id'] = $case->id;

        // If no date provided, use now
        if (empty($validated['transaction_date'])) {
            $validated['transaction_date'] = now();
        }

        // 🔹 Calculate already paid amount for this case
        $totalPaid = $case->transactions()->where('status', 'paid')->sum('amount');
        $remaining = $case->amount - $totalPaid;

        // 🔹 Validate amount: should not exceed remaining
        if ($validated['status'] === 'paid' && $validated['amount'] > $remaining) {
            return redirect()->route('cases.transactions.index', $case)
                ->with('warning', '⚠️ Entered amount exceeds remaining balance. Remaining: Rs ' . number_format($remaining, 0));

        }

        // ✅ Create the transaction
        Transaction::create($validated);

        // 🔹 Recalculate totals after saving
        $totalPaid = $case->transactions()->where('status', 'paid')->sum('amount');
        $remaining = $case->amount - $totalPaid;

        // 🔹 Update payment_status in CaseModel
        if ($remaining <= 0 && $case->amount > 0) {
            $case->payment_status = 'paid';
        } elseif ($totalPaid > 0 && $remaining > 0) {
            $case->payment_status = 'partial';
        } else {
            $case->payment_status = 'unpaid';
        }

        $case->save();

        return redirect()->route('cases.transactions.index', $case)
            ->with('success', 'Transaction created successfully.');
    }


    public function show(CaseModel $case, Transaction $transaction)
    {
        return view('transactions.show', compact('transaction', 'case'));
    }

    public function edit(CaseModel $case, Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction', 'case'));
    }

    public function update(Request $request, CaseModel $case, Transaction $transaction)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'nullable|in:credit,debit',
            'payment_method' => 'nullable|in:cash,bank,online,other',
            'transaction_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'required|in:paid,commission',
        ]);

        // If no date provided, use now
        if (empty($validated['transaction_date'])) {
            $validated['transaction_date'] = now();
        }

        // 🔹 Calculate already paid (excluding current transaction)
        $totalPaid = $case->transactions()
            ->where('status', 'paid')
            ->where('id', '!=', $transaction->id)
            ->sum('amount');

        $remaining = $case->amount - $totalPaid;

        // 🔹 Prevent exceeding remaining balance
        if ($validated['status'] === 'paid' && $validated['amount'] > $remaining) {
            return redirect()->route('cases.transactions.index', $case)
                ->with('warning', '⚠️ Entered amount exceeds remaining balance. Remaining: Rs ' . number_format($remaining, 0));
        }

        // ✅ Update transaction
        $transaction->update($validated);

        // 🔹 Recalculate totals after updating
        $totalPaid = $case->transactions()->where('status', 'paid')->sum('amount');
        $remaining = $case->amount - $totalPaid;

        // 🔹 Update payment_status in CaseModel
        if ($remaining <= 0 && $case->amount > 0) {
            $case->payment_status = 'paid';
        } elseif ($totalPaid > 0 && $remaining > 0) {
            $case->payment_status = 'partial';
        } else {
            $case->payment_status = 'unpaid';
        }

        $case->save();

        return redirect()->route('cases.transactions.index', $case)
            ->with('success', 'Transaction updated successfully.');
    }


    public function destroy(CaseModel $case, Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('cases.transactions.index', $case)
            ->with('success', 'Transaction deleted successfully.');
    }

    public function remaining_amount(Request $request)
    {
        if(auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'You do not have access to this page.');
        }
        $query = CaseModel::with(['client', 'transactions']);

        // 🔍 Search by case number or client name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 🔎 Filter by payment_status directly from CaseModel
        if ($request->filled('status')) {
            $status = $request->status;

            if (in_array($status, ['paid', 'unpaid', 'partial'])) {
                $query->where('payment_status', $status);
            }
        }

        $cases = $query->get();

        return view('transactions.remaining', compact('cases'));
    }


}
