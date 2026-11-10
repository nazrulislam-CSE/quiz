<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Eproduct extends Model
{
    protected $fillable = [
        'product_name',
        'slug',
        'product_photo',
        'brand_name',
        'size',
        'color',
        'price',
        'discount',
        'customer_price',
        'commission',
        'quantity',
        'description',
        'warranty',
        'status',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'discount'       => 'decimal:2',
        'customer_price' => 'decimal:2',
        'commission'     => 'decimal:2',
        'quantity'       => 'integer',
        'is_featured'    => 'boolean',
        'view_count'     => 'integer',
    ];

    /* ============================================================
       AUTO-GENERATE SLUG
    ============================================================ */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->product_name);
            }

            // Auto-calculate customer_price if discount set but customer_price is 0
            if ($product->customer_price == 0 && $product->price > 0) {
                $product->customer_price = $product->price - ($product->price * $product->discount / 100);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('product_name') && empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->product_name);
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /* ============================================================
       ACCESSORS — helper properties
    ============================================================ */

    /** Has discount? */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount > 0 && $this->customer_price < $this->price;
    }

    /** Discount amount in taka */
    public function getDiscountAmountAttribute(): float
    {
        return (float) max(0, $this->price - $this->customer_price);
    }

    /** Free product? */
    public function getIsFreeAttribute(): bool
    {
        return $this->price == 0 && $this->customer_price == 0;
    }

    /** Out of stock? */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->quantity <= 0;
    }

    /** In stock? */
    public function getIsInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    /** Product photo URL or default */
    public function getPhotoUrlAttribute(): string
    {
        return $this->product_photo
            ? asset('storage/' . $this->product_photo)
            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80';
    }

    /* ============================================================
       RELATIONS
    ============================================================ */

    /**
     * Orders of this product
     * (EproductOrder model তৈরি করলে কাজ করবে)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(EproductOrder::class, 'eproduct_id');
    }

    /* ============================================================
       SCOPES
    ============================================================ */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}