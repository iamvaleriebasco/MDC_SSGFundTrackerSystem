<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSG Fund Summary Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            background: #fff;
        }

        /*  Header  */
        .header {
            background: #1a3a6b;
            color: #fff;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .header .sub {
            font-size: 9pt;
            opacity: .75;
        }
        .header .meta {
            font-size: 8.5pt;
            opacity: .65;
            margin-top: 6px;
        }

        /*  Summary Boxes  */
        .summary-row {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-row td {
            width: 33%;
            padding: 0 6px;
            vertical-align: top;
        }
        .summary-box {
            border-radius: 6px;
            padding: 12px 14px;
            color: #fff;
        }
        .box-income  { background: #1e8449; }
        .box-expense { background: #c0392b; }
        .box-net     { background: #1a3a6b; }
        .summary-box .label { font-size: 8pt; opacity: .85; }
        .summary-box .value { font-size: 15pt; font-weight: bold; }

        /*  Section heading  */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1a3a6b;
            border-bottom: 2px solid #1a3a6b;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        /*  Table  */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table thead tr {
            background: #1a3a6b;
            color: #fff;
        }
        table.data-table thead th {
            padding: 7px 8px;
            font-size: 8.5pt;
            text-align: left;
            font-weight: bold;
        }
        table.data-table tbody tr:nth-child(even) { background: #f4f6f9; }
        table.data-table tbody td {
            padding: 6px 8px;
            font-size: 9pt;
            border-bottom: 1px solid #e8edf2;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .badge-active   { color: #1e8449; font-weight: bold; }
        .badge-inactive { color: #7f8c8d; }

        /*  Footer  */
        .footer {
            border-top: 1px solid #e8edf2;
            padding-top: 8px;
            font-size: 8pt;
            color: #95a5a6;
            text-align: center;
            margin-top: 16px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>SSG Fund Summary Report</h1>
        <div class="sub">Supreme Student Government — Fund Tracker System</div>
        <div class="meta">Generated on {{ $summary['generated_at'] }}</div>
    </div>

    <div style="padding: 0 24px;">

        {{-- Summary Boxes --}}
        <table class="summary-row" cellspacing="0" cellpadding="0">
            <tr>
                <td style="padding-right:6px;">
                    <div class="summary-box box-income">
                        <div class="label">Total Income</div>
                        <div class="value">&#8369;{{ number_format($summary['total_income'], 2) }}</div>
                    </div>
                </td>
                <td style="padding:0 3px;">
                    <div class="summary-box box-expense">
                        <div class="label">Total Expense</div>
                        <div class="value">&#8369;{{ number_format($summary['total_expense'], 2) }}</div>
                    </div>
                </td>
                <td style="padding-left:6px;">
                    <div class="summary-box box-net">
                        <div class="label">Net Balance</div>
                        <div class="value">&#8369;{{ number_format($summary['net_balance'], 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Fund Table --}}
        <div class="section-title">Fund Overview</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fund Name</th>
                    <th>School Year</th>
                    <th>Semester</th>
                    <th class="text-right">Allocated</th>
                    <th class="text-right">Income</th>
                    <th class="text-right">Expense</th>
                    <th class="text-right">Balance</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($funds as $i => $fund)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $fund->name }}</td>
                    <td>{{ $fund->school_year }}</td>
                    <td>{{ $fund->semester }}</td>
                    <td class="text-right">&#8369;{{ number_format($fund->allocated_amount, 2) }}</td>
                    <td class="text-right">&#8369;{{ number_format($fund->total_income, 2) }}</td>
                    <td class="text-right">&#8369;{{ number_format($fund->total_expense, 2) }}</td>
                    <td class="text-right"><strong>&#8369;{{ number_format($fund->current_balance, 2) }}</strong></td>
                    <td class="text-center {{ $fund->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ ucfirst($fund->status) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer --}}
        <div class="footer">
            SSG Fund Tracker &bull; This document is system-generated and does not require a signature unless otherwise specified.
        </div>

    </div>
</body>
</html>
