<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSubject extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function programs()
    {
        return $this->hasMany(Program::class, 'program_subject_id');
    }
    
    public function topics()
    {
        return $this->hasMany(ProgramTopic::class, 'program_subject_id');
    }


}
