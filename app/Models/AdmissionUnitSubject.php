<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionUnitSubject extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(AdmissionUnit::class, 'admission_unit_id');
    }

}
