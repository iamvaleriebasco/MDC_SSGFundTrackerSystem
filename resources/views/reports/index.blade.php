@extends('layouts.app')
@section('title', 'Reports – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Reports</h4>
        <small class="text-muted">Generate and download PDF reports</small>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-income">
            <div class="stat-label">Total Income (Approved)</div>
            <div class="stat-value">₱{{ number_format($summary['total_income'], 2) }}</div>
            <i class="bi bi-arrow-up-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-expense">
            <div class="stat-label">Total Expense (Approved)</div>
            <div class="stat-value">₱{{ number_format($summary['total_expense'], 2) }}</div>
            <i class="bi bi-arrow-down-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-funds">
            <div class="stat-label">Net Balance</div>
            <div class="stat-value">₱{{ number_format($summary['net_balance'], 2) }}</div>
            <i class="bi bi-cash-stack stat-icon"></i>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Fund Summary PDF --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Fund Summary Report
            </div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">
                    Generates a full overview of all funds including allocated amounts,
                    current balances, total income and expenses per fund.
                </p>

                <table class="table table-sm table-bordered" style="font-size:.82rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Fund</th>
                            <th>Allocated</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funds as $fund)
                        <tr>
                            <td>{{ $fund->name }}</td>
                            <td>₱{{ number_format($fund->allocated_amount, 2) }}</td>
                            <td>₱{{ number_format($fund->current_balance, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $fund->status === 'active' ? 'success' : 'secondary' }}-subtle
                                                  text-{{ $fund->status === 'active' ? 'success' : 'secondary' }}"
                                      style="font-size:.7rem;">
                                    {{ ucfirst($fund->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('reports.pdf') }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-download me-2"></i>Download Fund Summary PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Transaction Report PDF --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-file-earmark-spreadsheet me-2 text-primary"></i>Transaction Report
            </div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">
                    Generate a filtered transaction report by fund, type, or date range.
                    Download as a printable PDF.
                </p>

                <form method="GET" action="{{ route('reports.transactions.pdf') }}" class="row g-2">
                    <div class="col-12">
                        <label class="form-label small mb-1">Fund</label>
                        <select name="fund_id" class="form-select form-select-sm">
                            <option value="">All Funds</option>
                            @foreach($funds as $fund)
                                <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small mb-1">Transaction Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1">From Date</label>
                        <input type="date" name="from" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1">To Date</label>
                        <input type="date" name="to" class="form-control form-control-sm">
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-download me-2"></i>Download Transaction PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
