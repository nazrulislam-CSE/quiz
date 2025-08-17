<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['mcq_id', 'answer', 'is_correct'];

    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }
}
