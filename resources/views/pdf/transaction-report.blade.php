<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSG Transaction Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            color: #2c3e50;
            background: #fff;
        }

        .header {
            background: #1a3a6b;
            color: #fff;
            padding: 18px 24px;
            margin-bottom: 18px;
        }
        .header h1 { font-size: 15pt; font-weight: bold; margin-bottom: 3px; }
        .header .sub  { font-size: 8.5pt; opacity: .75; }
        .header .meta { font-size: 8pt; opacity: .6; margin-top: 5px; }

        .filter-bar {
            background: #f0f4f8;
            border-radius: 6px;
            padding: 8px 12px;
            margin: 0 24px 16px;
            font-size: 8.5pt;
            color: #5d6d7e;
        }
        .filter-bar span { margin-right: 16px; }

        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #1a3a6b;
            border-bottom: 2px solid #1a3a6b;
            padding-bottom: 4px;
            margin: 0 24px 10px;
        }

        table.data-table {
            width: calc(100% - 48px);
            margin: 0 24px;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.data-table thead tr { background: #1a3a6b; color: #fff; }
        table.data-table thead th {
            padding: 6px 7px;
            font-size: 8pt;
            text-align: left;
        }
        table.data-table tbody tr:nth-child(even) { background: #f8faff; }
        table.data-table tbody td {
            padding: 5px 7px;
            font-size: 8.5pt;
            border-bottom: 1px solid #e8edf2;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .type-income  { color: #1e8449; font-weight: bold; }
        .type-expense { color: #c0392b; font-weight: bold; }
        .status-approved { color: #1e8449; }
        .status-pending  { color: #d68910; }
        .status-rejected { color: #c0392b; }

        .totals-row td {
            background: #1a3a6b;
            color: #fff;
            font-weight: bold;
            padding: 7px 7px;
            font-size: 9pt;
        }

        .footer {
            border-top: 1px solid #e8edf2;
            padding: 8px 24px;
            font-size: 7.5pt;
            color: #95a5a6;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SSG Transaction Report</h1>
        <div class="sub">Supreme Student Government — Fund Tracker System</div>
        <div class="meta">Generated on {{ $meta['generated_at'] }}</div>
    </div>

    {{-- Filters applied --}}
    <div class="filter-bar">
        <span><strong>Fund:</strong> {{ $meta['fund'] ? $meta['fund']->name : 'All Funds' }}</span>
        <span><strong>Type:</strong> {{ $meta['type'] ? ucfirst($meta['type']) : 'All Types' }}</span>
        <span><strong>From:</strong> {{ $meta['from'] ?? 'Beginning' }}</span>
        <span><strong>To:</strong> {{ $meta['to'] ?? 'Present' }}</span>
        <span><strong>Total Records:</strong> {{ $transactions->count() }}</span>
    </div>

    <div class="section-title">Transaction Records</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Fund</th>
                <th>Member</th>
                <th>Type</th>
                <th>Category</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
                <th class="text-center">Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $tx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $tx->reference_number }}</td>
                <td>{{ $tx->fund->name }}</td>
                <td>{{ $tx->member->name ?? '—' }}</td>
                <td class="{{ $tx->type === 'income' ? 'type-income' : 'type-expense' }}">
                    {{ ucfirst($tx->type) }}
                </td>
                <td>{{ $tx->category }}</td>
                <td>{{ \Illuminate\Support\Str::limit($tx->description, 35) }}</td>
                <td class="text-right"><strong>&#8369;{{ number_format($tx->amount, 2) }}</strong></td>
                <td class="text-center status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</td>
                <td>{{ $tx->transaction_date->format('m/d/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding:14px; color:#95a5a6;">
                    No transactions match the selected filters.
                </td>
            </tr>
            @endforelse

            @if($transactions->count())
            <tr class="totals-row">
                <td colspan="7" class="text-right">Grand Total</td>
                <td class="text-right">&#8369;{{ number_format($meta['total'], 2) }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        SSG Fund Tracker &bull; This document is system-generated and does not require a signature unless otherwise specified.
    </div>

</body>
</html>
