@extends('layouts.app')
@section('title', 'Transactions – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item active">Transactions</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Transactions</h4>
        <small class="text-muted">All recorded fund transactions</small>
    </div>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Transaction
    </a>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small mb-1">Type</label>
                <select name="type" class="form-select form-select-sm" style="width:130px;">
                    <option value="">All Types</option>
                    <option value="income"  {{ request('type') === 'income'  ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" style="width:140px;">
                    <option value="">All Status</option>
                    @foreach(['pending','approved','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Fund</label>
                <select name="fund_id" class="form-select form-select-sm" style="width:180px;">
                    <option value="">All Funds</option>
                    @foreach($funds as $f)
                        <option value="{{ $f->id }}" {{ request('fund_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Fund</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td>
                            <a href="{{ route('transactions.show', $tx) }}"
                               class="text-decoration-none fw-600 text-primary"
                               style="font-size:.83rem;">
                                {{ $tx->reference_number }}
                            </a>
                        </td>
                        <td style="font-size:.83rem;">{{ $tx->fund->name }}</td>
                        <td style="font-size:.83rem;">{{ $tx->member->name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}"
                                  style="font-size:.72rem;">
                                {{ ucfirst($tx->type) }}
                            </span>
                        </td>
                        <td style="font-size:.83rem;">{{ $tx->category }}</td>
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
                            <div class="d-flex gap-1">
                                <a href="{{ route('transactions.show', $tx) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('transactions.edit', $tx) }}"
                                   class="btn btn-sm btn-outline-secondary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('transactions.destroy', $tx) }}"
                                      onsubmit="return confirm('Delete this transaction?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-arrow-left-right fs-3 d-block mb-2 opacity-30"></i>
                            No transactions found.
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
