<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkCorrectAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['homework_id', 'answer'];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
}
