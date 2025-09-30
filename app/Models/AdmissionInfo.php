<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function units()
    {
        return $this->hasMany(AdmissionUnit::class);
    }
}
