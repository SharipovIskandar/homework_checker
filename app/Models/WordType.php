<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordType extends Model
{

    protected $fillable = ['name'];

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
