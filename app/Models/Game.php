<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $fillable = ['game_type', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
