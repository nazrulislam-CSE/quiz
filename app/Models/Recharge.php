<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'number',
        'operator',
        'amount',
        'status',
        'provider_transaction_id',
        'response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'response' => 'array',
    ];


    /**
     * Recharge belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
