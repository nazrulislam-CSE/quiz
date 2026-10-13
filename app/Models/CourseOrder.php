<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_no',
        'price',
        'discount_price',
        'paid_amount',
        'payment_gateway',
        'merchant_transaction_id',
        'eps_transaction_id',
        'gateway_transaction_id',
        'payment_method',
        'gateway_response',
        'payment_status',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'discount_price'   => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
    ];

    /* ================= Relations ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /* ================= Accessors ================= */

    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }
}