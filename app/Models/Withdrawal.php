<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'amount',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'notes',
        'status',
        'iris_reference',
        'error_message',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-500/20 text-yellow-500',
            'processing' => 'bg-blue-500/20 text-blue-500',
            'success' => 'bg-green-500/20 text-green-500',
            'failed' => 'bg-red-500/20 text-red-500',
            default => 'bg-gray-500/20 text-gray-500',
        };
    }
}
