<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CaseModel;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(CaseModel $case)
    {
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
            'amount' => 'nullable|numeric',
            'type' => 'nullable|in:credit,debit',
            'payment_method' => 'nullable|in:cash,bank,online,other',
            'transaction_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:paid,commission',
        ]);

        $validated['case_id'] = $case->id;

        // If no date provided, use now
        if (empty($validated['transaction_date'])) {
            $validated['transaction_date'] = now();
        }

        Transaction::create($validated);

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
            'amount' => 'nullable|numeric',
            'type' => 'nullable|in:credit,debit',
            'payment_method' => 'nullable|in:cash,bank,online,other',
            'transaction_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'nullable|in:paid,pending',
        ]);

        $transaction->update($validated);

        return redirect()->route('cases.transactions.index', $case)
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(CaseModel $case, Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('cases.transactions.index', $case)
            ->with('success', 'Transaction deleted successfully.');
    }
}
