<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * TransactionReceiptMail
 *
 * Sends an automated email receipt to the member whenever a transaction
 * is recorded in the SSG Fund Tracker. Uses Laravel Queues so the email
 * is dispatched in the background (see SendTransactionReceiptJob).
 */
class TransactionReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Transaction $transaction
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SSG Transaction Receipt - ' . $this->transaction->reference_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction-receipt',
            with: [
                'transaction' => $this->transaction->load(['fund', 'member', 'recorder']),
            ],
        );
    }
}
