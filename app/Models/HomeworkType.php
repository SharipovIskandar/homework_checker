<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key'];

    public function homeworks()
    {
        return $this->hasMany(Homework::class, 'type_id');
    }
}
