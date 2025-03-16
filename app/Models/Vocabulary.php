<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{

    protected $fillable = ['word', 'level', 'due_date'];

    protected $casts = [
        'word' => 'array',
    ];

    public function testResults()
    {
        return $this->hasMany(VocabularyTestResult::class);
    }
}

