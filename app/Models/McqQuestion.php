<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }

    public function answers()
    {
        return $this->hasMany(McqAnswer::class);
    }
}
