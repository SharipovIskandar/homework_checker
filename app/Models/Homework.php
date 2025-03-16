<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory, Scopes;

    protected $table = 'homeworks';
    protected $fillable = ['subject_id', 'exercise_id', 'type_id', 'due_date', 'task_condition'];
    protected $casts = ['task_condition' => 'array'];

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

    public function homeworkTypes()
    {
        return $this->hasMany(HomeworkType::class, 'id', 'type_id');
    }

    public function homeworkSubmission()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}
