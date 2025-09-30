<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'department_id',
        'group_id',
        'subject_id',
        'topic_id',
        'model_test_id',
        'paper_final_id',
        'mcq_type', // 1=MCQ Topic Wise, 2=MCQ Study Question Topic Wise, 3=MCQ Paper Final Exam, 4=MCQ Model Test, 5=Manually MCQ
        'title',
        'exam_datetime',
        'exam_duration',
        'exam_mark',
        'pass_mark',
        'fee',
        'question',
        'created_by',
        'updated_by',
    ];

    public function answers()
    {
        return $this->hasMany(McqAnswer::class);
    }

    public function questions()
    {
        return $this->hasMany(McqQuestion::class);
    }


    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function modelTest()
    {
        return $this->belongsTo(ModelTest::class);
    }

    public function paperFinal()
    {
        return $this->belongsTo(PaperFinal::class);
    }

    public function quizAnswers()
    {
        return $this->hasMany(McqQuizAnswer::class, 'mcq_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
