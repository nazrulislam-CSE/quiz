<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTopic extends Model
{
    use HasFactory;
    protected $fillable = ['program_id','program_subject_id','topic_name','total_mcq','time','exam_fee'];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function subject()
    {
        return $this->belongsTo(ProgramSubject::class, 'program_subject_id');
    }
}
