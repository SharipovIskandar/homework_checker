<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkSubmission extends Model
{
    use HasFactory, Scopes;

    protected $fillable = ['student_id', 'homework_id', 'answers', 'status', 'score'];

    protected $casts = [
        'answers' => 'array',
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
