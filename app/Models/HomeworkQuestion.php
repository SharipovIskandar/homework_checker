<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkQuestion extends Model
{
    use HasFactory, Scopes;

    protected $fillable = ['homework_id', 'questions', 'correct_answers', 'answer_template', 'tip'];

    protected $casts = [
        'questions' => 'array',
        'correct_answers' => 'array',
        'answer_template' => 'array',
        'tip'=> 'array',
    ];


    public function getQuestionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setQuestionsAttribute($value)
    {
        $this->attributes['questions'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getCorrectAnswerAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setCorrectAnswerAttribute($value)
    {
        $this->attributes['correct_answers'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
