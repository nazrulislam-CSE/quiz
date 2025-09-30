<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'admission_info_id',
        'unit',
        'description',
        'note',
        'exam_date',
        'exam_time',
    ];

    public function admissionInfo()
    {
        return $this->belongsTo(AdmissionInfo::class);
    }
}
