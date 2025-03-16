<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHomeworkResult extends Model
{
    use HasFactory, Scopes;

    protected $fillable = ['student_id', 'homework_id', 'total_questions', 'correct_answers', 'score', 'incorrect_answers'];

    protected $casts = [
        'incorrect_answers' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
