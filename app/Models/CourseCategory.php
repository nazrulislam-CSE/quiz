<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'name',
        'slug',
        'status',
    ];

    protected $casts = ['status' => 'string'];

    /**
     * CourseCategory belongs to a CourseClass
     */
    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }
}