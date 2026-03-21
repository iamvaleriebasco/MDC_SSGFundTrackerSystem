<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    //Constants

    public const INCOME_CATEGORIES  = ['Membership Dues', 'Donation', 'Event Income', 'Subsidy', 'Other Income'];
    public const EXPENSE_CATEGORIES = ['Supplies', 'Event Expense', 'Transportation', 'Food & Beverage', 'Printing', 'Other Expense'];

    // Resource Methods

    public function index(Request $request)
    {
        $query = Transaction::with(['fund', 'member', 'recorder'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fund_id')) {
            $query->where('fund_id', $request->fund_id);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $funds        = Fund::where('status', 'active')->get();

        return view('transactions.index', compact('transactions', 'funds'));
    }

    public function create()
    {
        $funds   = Fund::where('status', 'active')->get();
        $members = Member::where('is_active', true)->orderBy('name')->get();

        return view('transactions.create', compact('funds', 'members', 'this'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fund_id'          => 'required|exists:funds,id',
            'member_id'        => 'nullable|exists:members,id',
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'description'      => 'required|string|max:1000',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'receipt_number'   => 'nullable|string|max:100',
        ]);

        $validated['recorded_by']      = Auth::id();
        $validated['reference_number'] = Transaction::generateReference();
        $validated['status']           = 'pending';

        $transaction = Transaction::create($validated);

        return redirect()->route('transactions.show', $transaction)
                         ->with('success', 'Transaction recorded. Reference: ' . $transaction->reference_number);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['fund', 'member', 'recorder']);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $funds   = Fund::where('status', 'active')->get();
        $members = Member::where('is_active', true)->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'funds', 'members', 'this'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'fund_id'          => 'required|exists:funds,id',
            'member_id'        => 'nullable|exists:members,id',
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'description'      => 'required|string|max:1000',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'receipt_number'   => 'nullable|string|max:100',
            'status'           => 'required|in:pending,approved,rejected',
        ]);

        //Only treasurer can approve or reject
        if ($validated['status'] !== $transaction->status) {
            if (Auth::user()->role !== 'treasurer') {
                return redirect()->back()
                                 ->with('error', 'Only the Treasurer is authorized to approve or reject transactions.');
            }
        }

        // Observer fires updated() → sends email if status changed to approved
        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)
                         ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
                         ->with('success', 'Transaction deleted successfully.');
    }
}
