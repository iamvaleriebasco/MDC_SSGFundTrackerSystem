<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $funds = Fund::withCount('transactions')->get();

        $summary = [
            'total_income'  => Transaction::where('type', 'income')->where('status', 'approved')->sum('amount'),
            'total_expense' => Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount'),
            'pending_count' => Transaction::where('status', 'pending')->count(),
        ];

        $summary['net_balance'] = $summary['total_income'] - $summary['total_expense'];

        return view('reports.index', compact('funds', 'summary'));
    }

    /**
     * Generate a full fund summary PDF report.
     * Uses spatie/laravel-pdf with the dompdf driver.
     */
    public function generatePdf()
    {
        $funds = Fund::with(['transactions'])->get();

        $summary = [
            'total_income'  => Transaction::where('type', 'income')->where('status', 'approved')->sum('amount'),
            'total_expense' => Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount'),
            'generated_at'  => now()->format('F d, Y h:i A'),
        ];

        $summary['net_balance'] = $summary['total_income'] - $summary['total_expense'];

        return Pdf::view('pdf.fund-summary', compact('funds', 'summary'))
                  ->format('a4')
                  ->name('SSG-Fund-Summary-' . now()->format('Ymd') . '.pdf')
                  ->download();
    }

    /**
     * Generate a transaction history PDF report with optional filters.
     */
    public function transactionPdf(Request $request)
    {
        $query = Transaction::with(['fund', 'member', 'recorder'])->latest();

        if ($request->filled('fund_id')) {
            $query->where('fund_id', $request->fund_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->to);
        }

        $transactions = $query->get();
        $fund         = $request->filled('fund_id') ? Fund::find($request->fund_id) : null;

        $meta = [
            'generated_at' => now()->format('F d, Y h:i A'),
            'fund'         => $fund,
            'from'         => $request->from,
            'to'           => $request->to,
            'type'         => $request->type,
            'total'        => $transactions->sum('amount'),
        ];

        return Pdf::view('pdf.transaction-report', compact('transactions', 'meta'))
                  ->format('a4')
                  ->name('SSG-Transactions-' . now()->format('Ymd') . '.pdf')
                  ->download();
    }
}
