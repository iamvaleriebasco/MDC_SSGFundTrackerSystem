@extends('layouts.app')
@section('title', $transaction->reference_number . ' – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transactions</a></li>
    <li class="breadcrumb-item active">{{ $transaction->reference_number }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Transaction Detail</h4>
        <small class="text-muted">{{ $transaction->reference_number }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt me-2 text-primary"></i>Transaction Receipt
            </div>
            <div class="card-body">
                {{-- Type + Amount Banner --}}
                <div class="p-3 rounded mb-4 text-center"
                    style="background: {{ $transaction->type === 'income' ? 'linear-gradient(135deg,#1e8449,#27ae60)' : 'linear-gradient(135deg,#c0392b,#e74c3c)' }}; color:#fff; border-radius:10px;">
                    <div style="font-size:.85rem; opacity:.85; text-transform:uppercase; letter-spacing:.5px;">
                        {{ ucfirst($transaction->type) }}
                    </div>
                    <div style="font-size:2rem; font-weight:700;">
                        ₱{{ number_format($transaction->amount, 2) }}
                    </div>
                    <div style="font-size:.82rem; opacity:.8;">{{ $transaction->category }}</div>
                </div>

                @php
                    $details = [
                        'Reference No.'    => $transaction->reference_number,
                        'Fund'             => $transaction->fund->name,
                        'Member'           => $transaction->member->name ?? '—',
                        'Description'      => $transaction->description,
                        'Transaction Date' => $transaction->transaction_date->format('F d, Y'),
                        'Receipt Number'   => $transaction->receipt_number ?? '—',
                        'Recorded By'      => $transaction->recorder->name,
                        'Recorded At'      => $transaction->created_at->format('M d, Y h:i A'),
                    ];
                @endphp

                @foreach($details as $label => $value)
                    <div class="d-flex py-2 border-bottom" style="font-size:.875rem;">
                        <span class="text-muted" style="width:150px; flex-shrink:0;">{{ $label }}</span>
                        <span class="fw-500">{{ $value }}</span>
                    </div>
                @endforeach

                <div class="d-flex py-2" style="font-size:.875rem;">
                    <span class="text-muted" style="width:150px; flex-shrink:0;">Status</span>
                    <span>
                        <span class="badge badge-{{ $transaction->status }} px-2 py-1" style="font-size:.8rem;">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                      onsubmit="return confirm('Permanently delete this transaction?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Delete Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        {{-- Fund Balance --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-wallet2 me-2 text-primary"></i>Fund Balance
            </div>
            <div class="card-body">
                @php $fund = $transaction->fund; @endphp
                <div class="text-center mb-3">
                    <div class="fw-700" style="font-size:1.6rem; color:#1a3a6b;">
                        ₱{{ number_format($fund->current_balance, 2) }}
                    </div>
                    <div class="text-muted" style="font-size:.8rem;">Current Balance</div>
                </div>
                @php
                    $pct = $fund->allocated_amount > 0
                         ? min(100, ($fund->current_balance / $fund->allocated_amount) * 100)
                         : 0;
                @endphp
                <div class="progress mb-2" style="height:8px; border-radius:4px;">
                    <div class="progress-bar bg-success" style="width:{{ $pct }}%;"></div>
                </div>
                <div class="d-flex justify-content-between" style="font-size:.78rem; color:#6b7280;">
                    <span>₱0</span>
                    <span>Allocated: ₱{{ number_format($fund->allocated_amount, 2) }}</span>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('funds.show', $fund) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i> View Fund
                    </a>
                </div>
            </div>
        </div>

        {{-- Approval Card --}}
        @if($transaction->status === 'pending')
            @if(Auth::user()->role === 'treasurer')
                {{-- TREASURER: show approve / reject buttons --}}
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-check2-circle me-2 text-warning"></i>Approval
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted mb-3" style="font-size:.85rem;">
                            This transaction is currently <strong>pending</strong> approval.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="fund_id"          value="{{ $transaction->fund_id }}">
                                <input type="hidden" name="member_id"        value="{{ $transaction->member_id }}">
                                <input type="hidden" name="type"             value="{{ $transaction->type }}">
                                <input type="hidden" name="category"         value="{{ $transaction->category }}">
                                <input type="hidden" name="description"      value="{{ $transaction->description }}">
                                <input type="hidden" name="amount"           value="{{ $transaction->amount }}">
                                <input type="hidden" name="transaction_date" value="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                <input type="hidden" name="receipt_number"   value="{{ $transaction->receipt_number }}">
                                <input type="hidden" name="status"           value="approved">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="fund_id"          value="{{ $transaction->fund_id }}">
                                <input type="hidden" name="member_id"        value="{{ $transaction->member_id }}">
                                <input type="hidden" name="type"             value="{{ $transaction->type }}">
                                <input type="hidden" name="category"         value="{{ $transaction->category }}">
                                <input type="hidden" name="description"      value="{{ $transaction->description }}">
                                <input type="hidden" name="amount"           value="{{ $transaction->amount }}">
                                <input type="hidden" name="transaction_date" value="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                <input type="hidden" name="receipt_number"   value="{{ $transaction->receipt_number }}">
                                <input type="hidden" name="status"           value="rejected">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-lg me-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                {{-- NON-TREASURER: read-only notice --}}
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-hourglass-split me-2 text-warning"></i>Pending Approval
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-2" style="font-size:2rem;">⏳</div>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            This transaction is awaiting approval.<br>
                            <strong>Only the Treasurer</strong> can approve or reject transactions.
                        </p>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
