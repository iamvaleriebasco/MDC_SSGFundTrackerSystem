<?php

namespace App\Models;

use App\Jobs\SendTransactionReceiptJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'fund_id',
        'member_id',
        'recorded_by',
        'type',
        'category',
        'description',
        'amount',
        'transaction_date',
        'receipt_number',
        'status',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::created(function (Transaction $transaction) {
            if ($transaction->type === 'income') {
                SendTransactionReceiptJob::dispatch($transaction);
            }
        });
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    //Helper

    public static function generateReference(): string
    {
        $prefix = 'SSG-' . date('Ymd') . '-';
        $last   = static::where('reference_number', 'like', $prefix . '%')
                        ->orderByDesc('id')
                        ->value('reference_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
