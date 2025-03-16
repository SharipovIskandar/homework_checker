<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordTranslation extends Model
{

    protected $fillable = ['word_id', 'language_id', 'translation'];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
