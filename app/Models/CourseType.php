<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    protected $casts = ['status' => 'string'];

    /**
     * CourseType has many CourseClasses
     */
    public function courseClasses()
    {
        return $this->hasMany(CourseClass::class);
    }
}