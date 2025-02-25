<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHomeworkResult extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'homework_id', 'total_questions', 'correct_answers', 'score'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
