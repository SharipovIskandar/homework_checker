<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $fillable = ['name', 'code'];

    public function translations()
    {
        return $this->hasMany(WordTranslation::class);
    }

    public function descriptions()
    {
        return $this->hasMany(WordDescription::class);
    }
}
