@extends('layouts.app')
@section('title', $fund->name . ' – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('funds.index') }}">Funds</a></li>
    <li class="breadcrumb-item active">{{ $fund->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">{{ $fund->name }}</h4>
        <small class="text-muted">{{ $fund->school_year }} – {{ $fund->semester }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('funds.edit', $fund) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('funds.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-funds">
            <div class="stat-label">Allocated Amount</div>
            <div class="stat-value">₱{{ number_format($fund->allocated_amount, 2) }}</div>
            <i class="bi bi-wallet2 stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-income">
            <div class="stat-label">Current Balance</div>
            <div class="stat-value">₱{{ number_format($fund->current_balance, 2) }}</div>
            <i class="bi bi-cash-coin stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-expense">
            <div class="stat-label">Total Expense</div>
            <div class="stat-value">₱{{ number_format($fund->total_expense, 2) }}</div>
            <i class="bi bi-arrow-down-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-arrow-left-right me-2 text-primary"></i>Transactions</span>
        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Transaction
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td style="font-size:.82rem;">{{ $tx->reference_number }}</td>
                        <td>
                            <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}"
                                  style="font-size:.72rem;">
                                {{ ucfirst($tx->type) }}
                            </span>
                        </td>
                        <td style="font-size:.83rem;">{{ $tx->category }}</td>
                        <td style="font-size:.83rem;">{{ Str::limit($tx->description, 40) }}</td>
                        <td class="fw-700">₱{{ number_format($tx->amount, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $tx->status }}" style="font-size:.72rem;">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                        <td style="font-size:.82rem; color:#6b7280;">
                            {{ $tx->transaction_date->format('M d, Y') }}
                        </td>
                        <td>
                            <a href="{{ route('transactions.show', $tx) }}"
                               class="btn btn-sm btn-outline-primary py-0 px-2">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4" style="font-size:.85rem;">
                            No transactions for this fund yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())
        <div class="card-footer bg-transparent">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
