<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'course_id',
        'order_no',
        'is_gift',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'student_name',
        'student_email',
        'student_phone',
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
        'is_gift'          => 'boolean',
        'paid_at'          => 'datetime',
    ];

    /* ================= Relations ================= */

    /**
     * Buyer (যিনি টাকা দিয়েছেন)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Student (যিনি Access পাবেন)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
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

    /**
     * Access owner — student থাকলে student, না হলে user
     */
    public function getAccessOwnerIdAttribute()
    {
        return $this->student_id ?? $this->user_id;
    }

    /**
     * Access owner name
     */
    public function getAccessOwnerNameAttribute()
    {
        return $this->student_name
            ?? $this->student?->name
            ?? $this->buyer_name
            ?? $this->user?->name
            ?? 'N/A';
    }
}