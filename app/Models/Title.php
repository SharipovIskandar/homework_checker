<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function studentTitles()
    {
        return $this->hasMany(StudentTitle::class);
    }
}
