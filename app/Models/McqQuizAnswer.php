<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqQuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','mcq_id', 'question_id', 'answer_id', 'time_taken'];

    public function question() {
        return $this->belongsTo(McqQuestion::class, 'question_id');
    }

    public function answer() {
        return $this->belongsTo(McqAnswer::class, 'answer_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
