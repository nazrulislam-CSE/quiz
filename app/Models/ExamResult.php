<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','admission_id','department_id','subject_id','topic_id',
        'total','correct','wrong','score','time_taken','given_answers'
    ];

    protected $casts = [
        'given_answers' => 'array',
    ];

    // 🔹 User Relation
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 🔹 Admission Relation
    public function admission(){
        return $this->belongsTo(Admission::class);
    }

    // 🔹 Department Relation
    public function department(){
        return $this->belongsTo(Department::class);
    }

    // 🔹 Subject Relation
    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    // 🔹 Topic Relation
    public function topic(){
        return $this->belongsTo(Topic::class);
    }
}
