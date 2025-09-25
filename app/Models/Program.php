<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    // Program.php
    public function topics()
    {
        return $this->hasMany(ProgramTopic::class);
    }

     public function subjects()
    {
        return $this->hasManyThrough(
            ProgramSubject::class,
            ProgramTopic::class,
            'program_id',           // Foreign key on program_topics
            'id',                   // Local key on program_subjects
            'id',                   // Local key on programs
            'program_subject_id'    // Foreign key on program_topics
        )->distinct();
    }




}
