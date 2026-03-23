<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Member;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFunds        = Fund::where('status', 'active')->count();
        $totalMembers      = Member::where('is_active', true)->count();
        $totalIncome       = Transaction::where('type', 'income')->where('status', 'approved')->sum('amount');
        $totalExpense      = Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount');
        $pendingCount      = Transaction::where('status', 'pending')->count();
        $recentTransactions = Transaction::with(['fund', 'member', 'recorder'])
                                        ->latest()
                                        ->take(10)
                                        ->get();

        $activeFunds = Fund::where('status', 'active')
                        ->withCount('transactions')
                        ->get();

        return view('dashboard', compact(
            'totalFunds',
            'totalMembers',
            'totalIncome',
            'totalExpense',
            'pendingCount',
            'recentTransactions',
            'activeFunds',
        ));
    }
}
