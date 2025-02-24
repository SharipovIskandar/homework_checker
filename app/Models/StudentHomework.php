<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHomework extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'homework_id', 'answers', 'score', 'status'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
