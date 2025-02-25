<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHomeworkAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['student_homework_id', 'question_id', 'student_answer', 'is_checked'];

    protected $casts = [
        'student_answer' => 'array',
    ];

    public function studentHomework()
    {
        return $this->belongsTo(StudentHomework::class);
    }

    public function question()
    {
        return $this->belongsTo(HomeworkQuestion::class);
    }
}
