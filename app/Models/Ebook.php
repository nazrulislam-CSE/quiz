<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ebook extends Model
{
    use HasFactory;

    protected $table = 'ebooks';

    protected $fillable = [
        'book_name',
        'slug',
        'author_name',
        'book_photo',
        'total_page',
        'price',
        'discount',
        'sell_price',
        'commission',
        'quantity',
        'description',
        'status',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'discount'    => 'decimal:2',
        'sell_price'  => 'decimal:2',
        'commission'  => 'decimal:2',
        'total_page'  => 'integer',
        'quantity'    => 'integer',
        'view_count'  => 'integer',
        'is_featured' => 'boolean',
    ];

    /* ============================================================
       BOOT — Auto-generate slug on create/update
    ============================================================ */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ebook) {
            if (empty($ebook->slug)) {
                $ebook->slug = static::generateUniqueSlug($ebook->book_name);
            }

            // Auto-calculate sell_price if not provided
            if ($ebook->sell_price <= 0 && $ebook->price > 0) {
                $ebook->sell_price = $ebook->has_discount
                    ? round($ebook->price - ($ebook->price * $ebook->discount / 100), 2)
                    : $ebook->price;
            }
        });

        static::updating(function ($ebook) {
            // Regenerate slug if book_name changed and slug not manually set
            if ($ebook->isDirty('book_name') && ! $ebook->isDirty('slug')) {
                $ebook->slug = static::generateUniqueSlug($ebook->book_name, $ebook->id);
            }
        });
    }

    /**
     * Generate unique slug for e-book
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $count    = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }

    /* ============================================================
       ACCESSORS
    ============================================================ */

    /**
     * Has active discount?
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->discount > 0 && $this->price > 0 && $this->sell_price < $this->price;
    }

    /**
     * Discount percentage (rounded)
     */
    public function getDiscountPercentAttribute(): int
    {
        return $this->discount > 0 ? (int) round($this->discount) : 0;
    }

    /**
     * Final price — sell_price is stored, but fallback to computed
     */
    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->sell_price ?: $this->price);
    }

    /**
     * Book photo full URL (with default fallback)
     */
    public function getBookPhotoUrlAttribute(): string
    {
        return $this->book_photo
            ? asset('storage/' . $this->book_photo)
            : 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&q=80';
    }

    /**
     * Is in stock?
     */
    public function getInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    /* ============================================================
       HELPERS
    ============================================================ */

    /**
     * Increment view count (session-based dedup on caller side)
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Recalculate sell_price based on price & discount
     * Call this before saving if you update price/discount
     */
    public function recalculateSellPrice(): void
    {
        $this->sell_price = $this->has_discount
            ? round($this->price - ($this->price * $this->discount / 100), 2)
            : $this->price;
    }
}