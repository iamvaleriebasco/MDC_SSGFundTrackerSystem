<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::withCount('transactions')->latest()->paginate(10);

        return view('funds.index', compact('funds'));
    }

    public function create()
    {
        return view('funds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'allocated_amount' => 'required|numeric|min:0',
            'school_year'      => 'required|string|max:20',
            'semester'         => 'required|string|max:20',
            'status'           => 'required|in:active,closed,archived',
        ]);

        $validated['current_balance'] = $validated['allocated_amount'];

        Fund::create($validated);

        return redirect()->route('funds.index')
                         ->with('success', 'Fund created successfully.');
    }

    public function show(Fund $fund)
    {
        $transactions = $fund->transactions()
                             ->with(['member', 'recorder'])
                             ->latest()
                             ->paginate(15);

        return view('funds.show', compact('fund', 'transactions'));
    }

    public function edit(Fund $fund)
    {
        return view('funds.edit', compact('fund'));
    }

    public function update(Request $request, Fund $fund)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'allocated_amount' => 'required|numeric|min:0',
            'school_year'      => 'required|string|max:20',
            'semester'         => 'required|string|max:20',
            'status'           => 'required|in:active,closed,archived',
        ]);

        $fund->update($validated);

        return redirect()->route('funds.index')
                         ->with('success', 'Fund updated successfully.');
    }

    public function destroy(Fund $fund)
    {
        $fund->delete();

        return redirect()->route('funds.index')
                         ->with('success', 'Fund deleted successfully.');
    }
}
