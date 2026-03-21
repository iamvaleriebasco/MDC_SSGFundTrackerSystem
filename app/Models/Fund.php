<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'allocated_amount',
        'current_balance',
        'school_year',
        'semester',
        'status',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'current_balance'  => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTotalIncomeAttribute(): float
    {
        return (float) $this->transactions()->where('type', 'income')->where('status', 'approved')->sum('amount');
    }

    public function getTotalExpenseAttribute(): float
    {
        return (float) $this->transactions()->where('type', 'expense')->where('status', 'approved')->sum('amount');
    }
}
