<?php

namespace App\Jobs;

use App\Mail\TransactionReceiptMail;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * SendTransactionReceiptJob
 *
 * Queued job that dispatches a transaction receipt email to the member.
 * Run the worker with: php artisan queue:work
 *
 * This satisfies the Laravel Queues & Workers requirement.
 */
class SendTransactionReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly Transaction $transaction
    ) {}

    public function handle(): void
    {
        $transaction = $this->transaction->load(['fund', 'member', 'recorder']);

        // Only send if a member with a valid email is associated
        if (! $transaction->member || ! $transaction->member->email) {
            return;
        }

        Mail::to($transaction->member->email)
            ->send(new TransactionReceiptMail($transaction));
    }
}
