<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $fillable = ['word_id', 'question', 'correct_answer'];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }

    public function options()
    {
        return $this->hasMany(TestOption::class);
    }
}
