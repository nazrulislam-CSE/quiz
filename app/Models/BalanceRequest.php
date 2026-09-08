<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method',
        'from_account',
        'amount',
        'trx_id',
        'screenshot',
        'status',
        'payment_status',

        // EPS Payment
        'payment_gateway',
        'merchant_transaction_id',
        'eps_transaction_id',
        'gateway_transaction_id',
        'payment_url',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}