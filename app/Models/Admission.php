<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    
}
