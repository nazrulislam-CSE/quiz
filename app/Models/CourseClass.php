<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_type_id',
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * CourseClass belongs to a CourseType
     */
    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
    }
}