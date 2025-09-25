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

   // Program.php
public function subjects()
{
    return $this->hasMany(ProgramSubject::class);
}



}
