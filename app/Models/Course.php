<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_type_id',
        'course_class_id',
        'course_category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'discount_price',
        'is_free',
        'total_class',
        'duration',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_free'        => 'boolean',
        'is_featured'    => 'boolean',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'view_count'     => 'integer',
    ];

    /* ================= Relations ================= */

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    /* ================= Accessors ================= */

    public function getFinalPriceAttribute()
    {
        if ($this->is_free) {
            return 0;
        }

        return $this->discount_price ?? $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return !$this->is_free
            && $this->discount_price !== null
            && $this->discount_price < $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        if (!$this->has_discount || $this->price <= 0) {
            return 0;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /* ================= View Counter ================= */

    /**
     * Increment view count (call this when a course is viewed)
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /* ================= Boot ================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . uniqid();
            }
        });
    }
}