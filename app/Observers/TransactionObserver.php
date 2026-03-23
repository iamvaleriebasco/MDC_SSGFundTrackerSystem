<?php

namespace App\Observers;

use App\Jobs\SendTransactionReceiptJob;
use App\Models\Transaction;
use App\Models\Fund;

/**
 * TransactionObserver
 *
 * - Income transactions are automatically approved on creation.
 * - Expense transactions require Treasurer approval.
 * - Fund balance is recalculated on every create, update, or delete.
 * - Email receipt is dispatched when an expense is approved by the Treasurer.
 */
class TransactionObserver
{
    /**
     * Fires after a transaction is created.
     * Income → auto-approve immediately.
     * Expense → stays pending, waits for Treasurer.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->type === 'income') {
            // updateQuietly skips firing the observer again (no infinite loop)
            $transaction->updateQuietly(['status' => 'approved']);
        }

        $this->recalculateFundBalance($transaction->fund_id);
    }

    /**
     * Fires after a transaction is updated.
     * Sends email receipt when an expense is approved by the Treasurer.
     */
    public function updated(Transaction $transaction): void
    {
        $this->recalculateFundBalance($transaction->fund_id);

        // If fund changed, recalculate the old fund too
        if ($transaction->wasChanged('fund_id')) {
            $this->recalculateFundBalance($transaction->getOriginal('fund_id'));
        }

        // Send email only when an expense status just changed TO approved
        if (
            $transaction->wasChanged('status') &&
            $transaction->status === 'approved' &&
            $transaction->type === 'expense' &&
            $transaction->member_id
        ) {
            SendTransactionReceiptJob::dispatch($transaction);
        }
    }

    /**
     * Fires after a transaction is deleted.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->recalculateFundBalance($transaction->fund_id);
    }

    // Private Helper

    private function recalculateFundBalance(int $fundId): void
    {
        $fund = Fund::find($fundId);

        if (! $fund) {
            return;
        }

        $totalIncome  = $fund->transactions()
                             ->where('type', 'income')
                             ->where('status', 'approved')
                             ->sum('amount');

        $totalExpense = $fund->transactions()
                             ->where('type', 'expense')
                             ->where('status', 'approved')
                             ->sum('amount');

        $fund->update([
            'current_balance' => $fund->allocated_amount + $totalIncome - $totalExpense,
        ]);
    }
}
