<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
    protected $fillable = [
        'user_id', 'ebook_id', 'order_no',
        'buyer_name', 'buyer_email', 'buyer_phone',
        'price', 'discount', 'paid_amount',
        'payment_gateway', 'merchant_transaction_id', 'eps_transaction_id',
        'gateway_transaction_id', 'payment_method', 'gateway_response',
        'payment_status', 'status', 'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'price'            => 'decimal:2',
        'discount'         => 'decimal:2',
        'paid_amount'      => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}