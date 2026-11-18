<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EproductOrder extends Model
{
    protected $fillable = [
        'user_id', 'eproduct_id', 'order_no',

        // Buyer
        'buyer_name', 'buyer_email', 'buyer_phone',

        // Shipping
        'shipping_name', 'shipping_phone', 'shipping_address',
        'shipping_city', 'shipping_postcode',

        // Delivery
        'delivery_type', 'shipping_charge',

        // Pricing
        'price', 'discount', 'product_total', 'paid_amount',

        // Payment
        'payment_gateway', 'merchant_transaction_id', 'eps_transaction_id',
        'gateway_transaction_id', 'payment_method', 'gateway_response',
        'payment_status', 'status', 'paid_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'price'            => 'decimal:2',
        'discount'         => 'decimal:2',
        'product_total'    => 'decimal:2',
        'shipping_charge'  => 'decimal:2',
        'paid_amount'      => 'decimal:2',
    ];

    /* ============================================================
       RELATIONS
    ============================================================ */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eproduct()
    {
        return $this->belongsTo(Eproduct::class, 'eproduct_id');
    }

    /* ============================================================
       HELPERS
    ============================================================ */

    /** Delivery type readable label */
    public function getDeliveryLabelAttribute(): string
    {
        return match ($this->delivery_type) {
            'inside_dhaka'  => 'Inside Dhaka',
            'outside_dhaka' => 'Outside Dhaka',
            'self_receive'  => 'Self Receive',
            default         => '—',
        };
    }

    /** Delivery charge (static helper for controller) */
    public static function getShippingCharge(string $deliveryType): float
    {
        return match ($deliveryType) {
            'inside_dhaka'  => 100.00,
            'outside_dhaka' => 130.00,
            'self_receive'  => 0.00,
            default         => 0.00,
        };
    }
}