<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
}
