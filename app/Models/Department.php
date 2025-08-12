<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id', 'id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

}
