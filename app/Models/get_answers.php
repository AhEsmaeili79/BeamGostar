<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class get_answers extends Model
{
    protected $table = 'get_answers';

    protected $fillable = [
        'title',
        'status',
    ];
}
