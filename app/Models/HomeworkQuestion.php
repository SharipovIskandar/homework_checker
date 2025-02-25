<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['homework_id', 'question', 'correct_answer'];

    protected $casts = [
        'question' => 'array',
        'correct_answer' => 'array',
    ];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
