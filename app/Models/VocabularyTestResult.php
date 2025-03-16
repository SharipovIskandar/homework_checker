<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabularyTestResult extends Model
{

    protected $fillable = ['vocabulary_id', 'user_id', 'correct_answers', 'incorrect_answers'];

    protected $casts = [
        'incorrect_answers' => 'array',
    ];

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
