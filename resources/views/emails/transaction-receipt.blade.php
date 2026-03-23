<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt – SSG Fund Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }
        .email-header {
            background: #1a3a6b;
            padding: 28px 32px;
            color: #ffffff;
        }
        .email-header h1 { font-size: 20px; margin: 0 0 4px; }
        .email-header p  { font-size: 13px; margin: 0; opacity: .75; }

        .email-body { padding: 28px 32px; }

        .greeting { font-size: 15px; margin-bottom: 16px; }

        .amount-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .amount-box.income  { background: #d4efdf; }
        .amount-box.expense { background: #fadbd8; }
        .amount-box .type   { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; color: #5d6d7e; }
        .amount-box .amount { font-size: 32px; font-weight: bold; }
        .income  .amount { color: #1e8449; }
        .expense .amount { color: #c0392b; }

        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .detail-table tr { border-bottom: 1px solid #f0f4f8; }
        .detail-table td { padding: 9px 6px; font-size: 13.5px; }
        .detail-table td:first-child { color: #7f8c8d; width: 140px; }
        .detail-table td:last-child   { font-weight: 600; }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending  { background: #fef9e7; color: #d68910; }
        .status-approved { background: #d4efdf; color: #1e8449; }
        .status-rejected { background: #fadbd8; color: #c0392b; }

        .note {
            background: #f8faff;
            border-left: 3px solid #2e6da4;
            padding: 12px 16px;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            color: #5d6d7e;
            margin-bottom: 20px;
        }

        .email-footer {
            background: #f8faff;
            padding: 16px 32px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #e8edf2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-header">
            <h1>SSG Fund Tracker</h1>
            <p>Supreme Student Government — Transaction Receipt</p>
        </div>

        <div class="email-body">
            <p class="greeting">
                Hi <strong>{{ $transaction->member->name }}</strong>,
            </p>
            <p style="font-size:13.5px; color:#5d6d7e; margin-bottom:18px;">
                A transaction has been recorded on your account. Here are the details:
            </p>

            <!-- Amount Box -->
            <div class="amount-box {{ $transaction->type }}">
                <div class="type">{{ ucfirst($transaction->type) }}</div>
                <div class="amount">&#8369;{{ number_format($transaction->amount, 2) }}</div>
            </div>

            <!-- Details -->
            <table class="detail-table">
                <tr>
                    <td>Reference No.</td>
                    <td>{{ $transaction->reference_number }}</td>
                </tr>
                <tr>
                    <td>Fund</td>
                    <td>{{ $transaction->fund->name }}</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>{{ $transaction->category }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{ $transaction->description }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $transaction->transaction_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td>Recorded By</td>
                    <td>{{ $transaction->recorder->name }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                </tr>
                @if($transaction->receipt_number)
                <tr>
                    <td>Receipt No.</td>
                    <td>{{ $transaction->receipt_number }}</td>
                </tr>
                @endif
            </table>

            <div class="note">
                <strong>Note:</strong> Please keep this email as your reference.
                If you have any questions about this transaction, contact your SSG treasurer.
            </div>
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} Supreme Student Government &bull; SSG Fund Tracker System<br>
            This is an automated notification. Please do not reply to this email.
        </div>
    </div>
</body>
</html>
