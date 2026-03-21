<?php

namespace App\Observers;

use App\Jobs\SendTransactionReceiptJob;
use App\Models\Transaction;
use App\Models\Fund;

/**
 * TransactionObserver
 *
 * - Automatically updates fund balance on create, update, delete.
 * - Dispatches email receipt ONLY when a transaction is approved.
 */
class TransactionObserver
{
    /**
     * Recalculate fund balance after a transaction is created.
     */
    public function created(Transaction $transaction): void
    {
        $this->recalculateFundBalance($transaction->fund_id);
    }

    /**
     * Recalculate fund balance after a transaction is updated.
     * Also sends email receipt if the transaction just became approved.
     */
    public function updated(Transaction $transaction): void
    {
        $this->recalculateFundBalance($transaction->fund_id);

        // If fund changed, recalculate the old fund too
        if ($transaction->wasChanged('fund_id')) {
            $this->recalculateFundBalance($transaction->getOriginal('fund_id'));
        }

        // Send email only when status just changed TO approved
        if (
            $transaction->wasChanged('status') &&
            $transaction->status === 'approved' &&
            $transaction->member_id
        ) {
            SendTransactionReceiptJob::dispatch($transaction);
        }
    }

    /**
     * Recalculate fund balance after a transaction is deleted.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->recalculateFundBalance($transaction->fund_id);
    }

    //Private Helper

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
