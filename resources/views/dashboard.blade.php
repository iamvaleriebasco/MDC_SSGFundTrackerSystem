@extends('layouts.app')

@section('title', 'Dashboard – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- ── Page Header ──────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Dashboard</h4>
        <small class="text-muted">Welcome back, {{ Auth::user()->name }}</small>
    </div>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Transaction
    </a>
</div>

{{-- ── Stat Cards ────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card bg-funds">
            <div class="stat-label">Active Funds</div>
            <div class="stat-value">{{ $totalFunds }}</div>
            <i class="bi bi-wallet2 stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-income">
            <div class="stat-label">Total Income</div>
            <div class="stat-value">₱{{ number_format($totalIncome, 2) }}</div>
            <i class="bi bi-arrow-up-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-expense">
            <div class="stat-label">Total Expense</div>
            <div class="stat-value">₱{{ number_format($totalExpense, 2) }}</div>
            <i class="bi bi-arrow-down-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-pending">
            <div class="stat-label">Pending Transactions</div>
            <div class="stat-value">{{ $pendingCount }}</div>
            <i class="bi bi-hourglass-split stat-icon"></i>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- ── Recent Transactions ──────────────────────────────────────────────── --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-arrow-left-right me-2 text-primary"></i>Recent Transactions</span>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reference</th>
                                <th>Fund</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $tx) }}"
                                       class="text-decoration-none fw-500 text-primary"
                                       style="font-size:.83rem;">
                                        {{ $tx->reference_number }}
                                    </a>
                                </td>
                                <td style="font-size:.83rem;">{{ $tx->fund->name }}</td>
                                <td>
                                    <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}"
                                          style="font-size:.72rem;">
                                        {{ ucfirst($tx->type) }}
                                    </span>
                                </td>
                                <td class="fw-600" style="font-size:.87rem;">
                                    ₱{{ number_format($tx->amount, 2) }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $tx->status }}" style="font-size:.72rem;">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem; color:#6b7280;">
                                    {{ $tx->transaction_date->format('M d, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4" style="font-size:.85rem;">
                                    <i class="bi bi-inbox me-2"></i>No transactions yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Fund Balances ────────────────────────────────────────────────────── --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-wallet2 me-2 text-primary"></i>Fund Balances</span>
                <a href="{{ route('funds.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @forelse($activeFunds as $fund)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <div class="fw-600" style="font-size:.85rem;">{{ $fund->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $fund->school_year }} – {{ $fund->semester }}
                                </div>
                            </div>
                            <span class="fw-700" style="font-size:.9rem; color:#1a3a6b;">
                                ₱{{ number_format($fund->current_balance, 2) }}
                            </span>
                        </div>
                        @php
                            $pct = $fund->allocated_amount > 0
                                 ? min(100, ($fund->current_balance / $fund->allocated_amount) * 100)
                                 : 0;
                            $color = $pct > 60 ? '#1e8449' : ($pct > 30 ? '#d68910' : '#c0392b');
                        @endphp
                        <div class="progress" style="height:6px; border-radius:4px;">
                            <div class="progress-bar"
                                 style="width:{{ $pct }}%; background:{{ $color }};">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted" style="font-size:.7rem;">
                                {{ number_format($pct, 0) }}% of ₱{{ number_format($fund->allocated_amount, 2) }}
                            </small>
                        </div>
                    </div>
                    @if(! $loop->last)<hr class="my-2">@endif
                @empty
                    <p class="text-center text-muted py-3 mb-0" style="font-size:.85rem;">
                        <i class="bi bi-inbox me-2"></i>No active funds.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
