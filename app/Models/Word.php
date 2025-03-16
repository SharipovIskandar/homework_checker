<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

    protected $fillable = ['word', 'word_type_id'];

    public function type()
    {
        return $this->belongsTo(WordType::class, 'word_type_id');
    }

    public function translations()
    {
        return $this->hasMany(WordTranslation::class);
    }

    public function descriptions()
    {
        return $this->hasMany(WordDescription::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
