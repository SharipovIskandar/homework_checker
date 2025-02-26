<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';
    protected $fillable = ['subject_id', 'exercise_id', 'type_id', 'due_date'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function type()
    {
        return $this->belongsTo(HomeworkType::class, 'type_id');
    }

    public function correctAnswers()
    {
        return $this->hasMany(HomeworkCorrectAnswer::class);
    }

    public function studentHomeworks()
    {
        return $this->hasMany(StudentHomework::class);
    }
}
